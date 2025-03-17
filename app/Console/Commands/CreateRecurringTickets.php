<?php
// app/Console/Commands/CreateRecurringTickets.php
namespace App\Console\Commands;

use App\Models\Ticket;
use Illuminate\Console\Command;
use Carbon\Carbon;

class CreateRecurringTickets extends Command
{
    protected $signature = 'tickets:generate-recurring';
    protected $description = 'Generate recurring tickets based on frequency';

    public function handle()
    {
        $today = Carbon::today();

        $recurringTickets = Ticket::where('is_recurring', true)
            ->whereNull('parent_ticket_id')
            ->where('recurrence_active', true)
            ->get();

        foreach ($recurringTickets as $ticket) {
            $shouldCreate = false;
            $lastCreation = Ticket::where('parent_ticket_id', $ticket->id)
                ->orderBy('created_at', 'desc')
                ->first();

            $nextCreation = $lastCreation
                ? Carbon::parse($lastCreation->created_at)
                : Carbon::parse($ticket->created_at);

            switch ($ticket->recurrence_frequency) {
                case 'daily':
                    $nextCreation->addDay();
                    $shouldCreate = $nextCreation->isToday();
                    break;
                case 'weekly':
                    $nextCreation->next($ticket->recurrence_day_of_week); // Próximo día específico
                    $shouldCreate = $nextCreation->isToday();
                    break;
                case 'monthly':
                    $nextCreation->addMonthNoOverflow()->day($ticket->recurrence_day_of_month);
                    $shouldCreate = $nextCreation->isToday();
                    break;
            }

            if ($shouldCreate && Carbon::now()->format('H:i:s') >= $ticket->recurrence_time) {
                Ticket::create([
                    'title' => $ticket->title,
                    'description' => $ticket->description,
                    'is_recurring' => false,
                    'parent_ticket_id' => $ticket->id,
                    'recurrence_time' => $ticket->recurrence_time,
                ]);
            }
        }

        $this->info('Recurring tickets processed successfully');
    }
}
