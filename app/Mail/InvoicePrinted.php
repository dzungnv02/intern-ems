<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Invoice;
use PDF;

class InvoicePrinted extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    public $invoice;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Invoice $invoice)
    {
        $this->invoice = $invoice;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $pdf = \PDF::loadHTML($this->invoice->invoice_content)
                    ->setOption('images', true)
                    ->setOption('encoding', 'UTF-8')
                    ->setOption('enable-javascript', true)
                    ->setOption('javascript-delay', 2000)
                    ->setOption('no-stop-slow-scripts', true)
                    ->setOption('enable-smart-shrinking', true)
                    ->setPaper('a5')->setOrientation('landscape');
        $pdf->setTimeout(300);

        return $this->view('invoice.detail.tutorfee_print_mail')
            ->attachData($pdf->inline(), 'invoice.pdf', [
                'mime' => 'application/pdf',
            ]);
    }
}
