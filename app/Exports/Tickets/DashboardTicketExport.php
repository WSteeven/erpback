<?php

namespace App\Exports\Tickets;

use App\Http\Resources\TicketResource;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Log;
use Maatwebsite\Excel\Concerns\WithBackgroundColor;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DashboardTicketExport implements FromView, WithColumnWidths, WithBackgroundColor, WithStyles, WithTitle
{
    protected $listado;
    protected $title;
    const TOTAL_FILAS_ENCABEZADO = 1;

    function __construct($listado, $title)
    {
        // $this->reporte = $reporte;
        $this->listado = $listado->map(fn($ticket) => (new TicketResource($ticket))->resolve());
        $this->title = $title;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 24,
            'B' => 30,
            'C' => 60,
            'D' => 40,
            'E' => 40,
            'F' => 30,
            'G' => 30,
            'H' => 30,
            'I' => 30,
            'J' => 30,
            'K' => 30,
            'L' => 40,
            'M' => 30,
        ];
    }

    public function backgroundColor()
    {
        return new Color(Color::COLOR_WHITE);
    }

    public function styles(Worksheet $sheet)
    {
        $textoTitulo = [
            'font' => [
                'bold' => true,
                'color' => [
                    'argb' => '000000',
                ],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
        ];

        $textCenter = [
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
        ];

        $bordeTabla = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ];

        $totalFilas = count($this->listado) + self::TOTAL_FILAS_ENCABEZADO;

        $sheet->getStyle('A1:M1')->applyFromArray($textoTitulo);
        $sheet->getStyle('A1:M' . $totalFilas)->applyFromArray($textCenter);
        $sheet->getStyle('A1:M' . $totalFilas)->applyFromArray($bordeTabla);
        $sheet->getStyle('A1:M' . $totalFilas)->getAlignment()->setWrapText(true);
        $sheet->getStyle('A1:A' . $totalFilas)->getFont()->setBold(true);
        $sheet->setAutoFilter('A1:M1');
    }

    public function view(): View
    {
        return view('tickets.excel.dashboard_ticket', ['reporte' => $this->listado, 'sumaTiempoOcupado' => $this->getSumaTiempoOcupado()]);
    }

    private function getSumaTiempoOcupado()
    {
        $totalSegundos = $this->listado->reduce(function ($carry, $ticket) {
            $tiempo = trim($ticket['tiempo_hasta_finalizar_h_m_s'] ?? '');
    
            if ($tiempo === '') {
                return $carry;
            }
    
            $partes = explode(':', $tiempo);
            if (count($partes) !== 3) {
                return $carry; // ignora si no es HH:MM:SS
            }
    
            [$horas, $minutos, $segundos] = array_map('intval', $partes);
    
            return $carry + ($horas * 3600) + ($minutos * 60) + $segundos;
        }, 0);
    
        $horas = floor($totalSegundos / 3600);
        $minutos = floor(($totalSegundos % 3600) / 60);
        $segundos = $totalSegundos % 60;
    
        $suma = sprintf('%02d:%02d:%02d', $horas, $minutos, $segundos);
        // Log::channel('testing')->info('Log', ['suma tickets', $suma]);
        return $suma;
    }
}
