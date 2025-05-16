<?php
// app/Console/Commands/CreateRecurringTickets.php
namespace App\Console\Commands;

use App\Models\Ticket;
use Illuminate\Console\Command;
use Carbon\Carbon;
use Exception;
use Log;

class CreateRecurringTickets extends Command
{
    protected $signature = 'tickets:generate-recurring';
    protected $description = 'Generate recurring tickets based on frequency';

    public function handle()
    {
        $recurringTickets = Ticket::where('is_recurring', true)
            ->whereNull('parent_ticket_id')
            ->where('recurrence_active', true)
            ->get();

        foreach ($recurringTickets as $ticket) {
            $shouldCreate = false;
            $lastTicketCreated = Ticket::where('parent_ticket_id', $ticket->id)
                ->orderBy('created_at', 'desc')
                ->first();

            $nextCreation = $lastTicketCreated
                ? Carbon::parse($lastTicketCreated->created_at)
                : Carbon::parse($ticket->created_at);

            switch ($ticket->recurrence_frequency) {
                case 'DAILY':
                    $nextCreation->addDay();
                    $shouldCreate = $nextCreation->isToday();
                    break;
                case 'WEEKLY':
                    $nextCreation->next($ticket->recurrence_day_of_week); // Próximo día específico
                    $shouldCreate = $nextCreation->isToday();
                    break;
                case 'MONTHLY':
                    $nextCreation->addMonthNoOverflow()->day($ticket->recurrence_day_of_month);
                    $shouldCreate = $nextCreation->isToday();
                    break;
            }

            if ($shouldCreate && Carbon::now()->format('H:i:s') >= $ticket->recurrence_time) {

                try {
                    $newTicket = $ticket->replicate();
                    $newTicket->codigo = null;
                    $newTicket->estado = Ticket::ASIGNADO;
                    $newTicket->is_recurring = false;
                    $newTicket->parent_ticket_id = $ticket->id;
                    $newTicket->save();
                } catch (Exception $e) {
                    Log::channel('testing')->info('Log', ['error' => $e->getMessage(), 'line' => $e->getLine()]);
                }
            }
        }
    }
}
