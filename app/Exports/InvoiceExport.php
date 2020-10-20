<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Invoice;


/**
 * class InvoicesExport
 * see https://docs.laravel-excel.com/3.1/exports/collection.html
 */
class InvoicesExport implements FromCollection, WithHeadings, WithEvents
{

    public function headings(): array
    {
        return [
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
        ];
    }

    public function registerEvents(): array
    {
        return [];
    }

    public function collection()
    {

    }

    public function storeExcel()
    {
        //return Excel::store(new InvoicesExport, 'invoices.xlsx', 's3');
    }

}
