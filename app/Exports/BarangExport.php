<?php

namespace App\Exports;

use App\Models\Item;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BarangExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithEvents, WithTitle
{
    private const PRIMARY = '14532D';
    private const HEADER = '166534';
    private const ZEBRA = 'F0FDF4';
    private const BORDER = 'D0D7DE';

    public function __construct(
        private readonly ?int $categoryId = null,
        private readonly ?string $search = null,
    ) {}

    public function query(): Builder
    {
        $query = Item::with('category')->latest();

        if ($this->categoryId) {
            $query->where('category_id', $this->categoryId);
        }

        if ($this->search) {
            $query->where(function (Builder $q) {
                $q->where('name', 'like', "%{$this->search}%")
                    ->orWhere('notes', 'like', "%{$this->search}%");
            });
        }

        return $query;
    }

    public function headings(): array
    {
        return ['Nama Barang', 'Kategori', 'Stok', 'Satuan', 'Harga Satuan'];
    }

    public function map($item): array
    {
        return [
            $item->name,
            ucfirst($item->category->name ?? 'Uncategorized'),
            $item->stock,
            $item->unit,
            'Rp '.number_format($item->price ?? 0, 0, ',', '.'),
        ];
    }

    public function title(): string
    {
        return 'Data Barang';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                $sheet->insertNewRowBefore(1, 3);

                $lastColumn = $sheet->getHighestDataColumn();
                $lastRow = $sheet->getHighestDataRow();

                $this->applyTitle($sheet, $lastColumn);
                $this->applyPrintedAt($sheet, $lastColumn);
                $this->applyHeaderStyle($sheet, $lastColumn);
                $this->applyDataStyle($sheet, $lastColumn, $lastRow);

                $sheet->freezePane('A5');
            },
        ];
    }

    private function applyTitle(Worksheet $sheet, string $lastColumn): void
    {
        $sheet->mergeCells("A1:{$lastColumn}1");
        $sheet->getRowDimension(1)->setRowHeight(32);
        $sheet->setCellValue('A1', 'DATA BARANG');

        $sheet->getStyle("A1:{$lastColumn}1")->applyFromArray([
            'font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => self::PRIMARY]],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);
    }

    private function applyPrintedAt(Worksheet $sheet, string $lastColumn): void
    {
        $sheet->mergeCells("A2:{$lastColumn}2");
        $sheet->getRowDimension(2)->setRowHeight(20);
        $sheet->setCellValue('A2', 'Dicetak: '.now()->format('d/m/Y H:i'));

        $sheet->getStyle("A2:{$lastColumn}2")->applyFromArray([
            'font' => ['italic' => true, 'size' => 10, 'color' => ['rgb' => '6B7280']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT],
        ]);
    }

    private function applyHeaderStyle(Worksheet $sheet, string $lastColumn): void
    {
        $sheet->getRowDimension(4)->setRowHeight(22);

        $sheet->getStyle("A4:{$lastColumn}4")->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => self::HEADER]],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => self::BORDER]]],
        ]);
    }

    private function applyDataStyle(Worksheet $sheet, string $lastColumn, int $lastRow): void
    {
        $sheet->getStyle("A5:{$lastColumn}{$lastRow}")->applyFromArray([
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_LEFT,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => self::BORDER]]],
        ]);

        $sheet->getStyle("C5:E{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        for ($row = 5; $row <= $lastRow; $row++) {
            if ($row % 2 === 0) {
                $sheet->getStyle("A{$row}:{$lastColumn}{$row}")
                    ->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setRGB(self::ZEBRA);
            }
        }
    }
}
