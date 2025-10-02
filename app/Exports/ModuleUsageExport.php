<?php

namespace App\Exports;

use App\Models\Log;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ModuleUsageExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $startDate;
    protected $endDate;
    protected $moduleType;

    public function __construct($startDate = null, $endDate = null, $moduleType = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->moduleType = $moduleType;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $query = Log::with('user:id,name,email');

        // Filtrar por fechas si se proporcionan
        if ($this->startDate) {
            $query->where('created_at', '>=', $this->startDate);
        }
        if ($this->endDate) {
            $query->where('created_at', '<=', $this->endDate);
        }

        // Filtrar por tipo de módulo si se proporciona
        if ($this->moduleType) {
            $query->where('type', $this->moduleType);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'Módulo/App',
            'Usuario',
            'Email',
            'Actividad',
            'Código Estado',
            'Método HTTP',
            'URL',
            'IP Address',
            'Tiempo Respuesta (ms)',
            'Tipo de Error',
            'Archivo Error',
            'Línea Error',
            'Fecha y Hora',
            'Fecha',
            'Hora'
        ];
    }

    /**
     * @param mixed $row
     */
    public function map($row): array
    {
        $errorDetails = null;
        if ($row->error_details) {
            $errorDetails = json_decode($row->error_details, true);
        }
        
        return [
            $row->id,
            ucfirst($row->type),
            $row->user ? $row->user->name : 'Usuario eliminado',
            $row->user ? $row->user->email : 'N/A',
            $row->message,
            $row->status_code,
            $row->method ?? 'N/A',
            $row->url ?? 'N/A',
            $row->ip_address ?? 'N/A',
            $row->response_time ?? 'N/A',
            $errorDetails['exception_class'] ?? 'N/A',
            $errorDetails['file'] ?? 'N/A',
            $errorDetails['line'] ?? 'N/A',
            $row->created_at->format('Y-m-d H:i:s'),
            $row->created_at->format('Y-m-d'),
            $row->created_at->format('H:i:s')
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Estilo para la primera fila (encabezados)
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF']
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4F46E5']
                ]
            ]
        ];
    }
}