<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Invoice;

class InvoiceController extends Controller
{
    public function download(Request $request)
    {
        $invoice = Invoice::findOrFail($request->input('id'));

        return response()->download(base_path('/invoices/'. $invoice->pdf_path));
    }
}
