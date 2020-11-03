<?php
namespace App\Exports;

use Illuminate\Support\Collection as Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;


/**
 * class InvoiceExport
 * see https://docs.laravel-excel.com/3.1/exports/collection.html
 */
class InvoiceExport implements FromCollection, WithHeadings, WithEvents, ShouldAutoSize
{
    use RegistersEventListeners;

    protected $data;
    protected $branch_name;
    protected $date_range;

    public function __construct(Collection $data, String $branch_name, String $date_range)
    {
        $this->data = $data;
        $this->branch_name = $branch_name;
        $this->date_range = $date_range;
    }

    public function headings(): array
    {
        return [
            [$this->branch_name . ' - Invoice list' . $this->date_range],
            [
                'No.',
                'Collecting Date',
                'No of Recept',
                'Code',
                'Name',
                'Parent Name',
                'Payment Method',
                'Course Name',
                'Discount',
                '',
                '',
                'Amount',
            ],
            [
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                'Discount total',
                'Type',
                'Desc.'
            ],
        ];
    }

    public function collection()
    {
        return $this->data;
    }

    public static function afterSheet(AfterSheet $e)
    {
        $e->sheet->getDelegate()->mergeCells('A1:L1');
        $e->sheet->getDelegate()->mergeCells('I2:K2');

        $cellRange = 'A2:L3';
        $e->sheet->getDelegate()
            ->getStyle($cellRange)
            ->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()
            ->setARGB('C0C0C0');
    }

}
