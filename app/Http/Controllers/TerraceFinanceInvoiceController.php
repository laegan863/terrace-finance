<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;

class TerraceFinanceInvoiceController extends Controller
{
    private function sample(): array
    {
        $request = [
            'InvoiceNumber' => 'DLX1367',
            'InvoiceDate' => '02-12-2025',
            'ApplicationID' => 108597,
            'LeadID' => null,
            'DeliveryDate' => '02-13-2025',
            'Discount' => 10.00,
            'DownPayment' => 100.00,
            'Shipping' => 10.50,
            'Tax' => 10.50,
            'Items' => [
                [
                    'ItemDescription' => 'Google Pixel 9',
                    'Brand' => 'Google',
                    'SerialNumber' => 'SRNO963',
                    'SKU' => 'STKEUN1076',
                    'Condition' => 'CPO',
                    'Price' => 850.00,
                    'Quantity' => 1,
                    'Discount' => 0.00,
                ],
                [
                    'ItemDescription' => 'Protective Case',
                    'Brand' => 'Google',
                    'SerialNumber' => null,
                    'SKU' => 'CASE-0001',
                    'Condition' => 'New',
                    'Price' => 49.99,
                    'Quantity' => 1,
                    'Discount' => 0.00,
                ],
            ],
            'ReturnURL' => 'https://www.yourwebsite.com/returnpage',
            'InvoiceVersion' => null,
        ]; // matches the example request format and date format requirements :contentReference[oaicite:6]{index=6}

        $response = [
            'Result' => [
                'ApplicationID' => 89383,
                'ApplicationStatus' => 'Approved',
                'SigningUrl' => 'https://terracefinance-ux-qa.azurewebsites.net/6c78f897-d0dd-4d5c-835c-848d137e26e6/signingRoom/cf-offer',
                'ApprovalAmount' => 1675.00,
                'Offer' => [
                    [
                        'BiWeeklyAmount' => 75.04,
                        'BiWeeklyNumPayments' => 48,
                        'MonthlyAmount' => 150.08,
                        'MonthlyNumPayments' => 24,
                        'PriceSheet' => 'core',
                        'PaymentFrequency' => 'BiWeekly',
                        'PaymentAmount' => 75.04,
                        'AmountDueAtSigning' => 99.00,
                        'NumberOfPayments' => 48,
                        'Promotion' => null,
                        'PromotionPeriod' => 90,
                        'PromotionCost' => 120.00,
                        'MDR' => 8.5,
                        'MDRType' => 'Percent',
                    ],
                    [
                        'BiWeeklyAmount' => 75.04,
                        'BiWeeklyNumPayments' => 48,
                        'MonthlyAmount' => 150.08,
                        'MonthlyNumPayments' => 24,
                        'PriceSheet' => 'core',
                        'PaymentFrequency' => 'Monthly',
                        'PaymentAmount' => 150.08,
                        'AmountDueAtSigning' => 99.00,
                        'NumberOfPayments' => 24,
                        'Promotion' => null,
                        'PromotionPeriod' => 90,
                        'PromotionCost' => 120.00,
                        'MDR' => 8.5,
                        'MDRType' => 'Percent',
                    ],
                ],
                'Lender' => [
                    'LenderID' => 1,
                    'LenderName' => 'Vernance LLC',
                ],
            ],
            'IsSuccess' => true,
            'Message' => 'Success',
            'Error' => null,
            'Errors' => null,
        ]; // response fields per pages 18–20 :contentReference[oaicite:7]{index=7}

        return compact('request', 'response');
    }

    private function recentInvoices(): array
    {
        return [
            [
                'InvoiceNumber' => 'DLX1367',
                'ApplicationID' => 108597,
                'LeadID' => null,
                'InvoiceDate' => '02-12-2025',
                'DeliveryDate' => '02-13-2025',
                'ApplicationStatus' => 'Approved',
                'ApprovalAmount' => 1675.00,
                'CreatedAt' => 'Jan 13, 2026 09:22 AM',
            ],
            [
                'InvoiceNumber' => 'INV-2026-0008',
                'ApplicationID' => 99044,
                'LeadID' => null,
                'InvoiceDate' => '01-12-2026',
                'DeliveryDate' => '01-13-2026',
                'ApplicationStatus' => 'Pending Invoice',
                'ApprovalAmount' => 1500.00,
                'CreatedAt' => 'Jan 12, 2026 02:18 PM',
            ],
            [
                'InvoiceNumber' => 'INV-2026-0005',
                'ApplicationID' => null,
                'LeadID' => 1234,
                'InvoiceDate' => '01-10-2026',
                'DeliveryDate' => '01-11-2026',
                'ApplicationStatus' => 'Draft',
                'ApprovalAmount' => null,
                'CreatedAt' => 'Jan 10, 2026 11:40 AM',
            ],
        ];
    }

    public function index()
    {
        $sample = $this->sample();

        return view('terrace-finance.invoices.index', [
            'result' => $sample,
            'recentInvoices' => $this->recentInvoices(),
        ]);
    }

    public function history()
    {
        return view('terrace-finance.invoices.history', [
            'invoices' => $this->recentInvoices(), // sample rows for now
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'InvoiceNumber' => ['required', 'string', 'min:1', 'max:256'],
            'InvoiceDate' => ['required', 'string', 'max:10'],   // mm-dd-yyyy
            'DeliveryDate' => ['required', 'string', 'max:10'],  // mm-dd-yyyy

            'ApplicationID' => ['nullable', 'integer', 'required_without:LeadID'],
            'LeadID' => ['nullable', 'integer', 'required_without:ApplicationID'],

            'Discount' => ['required', 'numeric'],
            'DownPayment' => ['required', 'numeric'],
            'Shipping' => ['required', 'numeric'],
            'Tax' => ['required', 'numeric'],

            'ReturnURL' => ['nullable', 'string', 'max:2048'],
            'InvoiceVersion' => ['nullable', 'in:C,R'],

            'Items' => ['required', 'array', 'min:1'],
            'Items.*.ItemDescription' => ['required', 'string', 'min:2', 'max:128'],
            'Items.*.Brand' => ['required', 'string', 'min:2', 'max:64'],
            'Items.*.SerialNumber' => ['nullable', 'string', 'max:128'],
            'Items.*.SKU' => ['required', 'string', 'min:2', 'max:32'],

            // Condition allowed values: New, Used, CPO, Refurbished, Parts, Salvage :contentReference[oaicite:8]{index=8}
            'Items.*.Condition' => ['required', 'in:New,Used,CPO,Refurbished,Parts,Salvage'],

            'Items.*.Price' => ['required', 'numeric'],
            'Items.*.Quantity' => ['required', 'integer', 'min:1'],
            'Items.*.Discount' => ['nullable', 'numeric'],
        ]);

        // Enforce mm-dd-yyyy for InvoiceDate and DeliveryDate :contentReference[oaicite:9]{index=9}
        foreach (['InvoiceDate', 'DeliveryDate'] as $field) {
            try {
                $data[$field] = Carbon::createFromFormat('m-d-Y', $data[$field])->format('m-d-Y');
            } catch (\Throwable $e) {
                return back()->withInput()->withErrors([
                    $field => $field . ' must be in mm-dd-yyyy format.',
                ]);
            }
        }

        // Normalize invoice version: treat empty string as null
        if (empty($data['InvoiceVersion'])) {
            $data['InvoiceVersion'] = null;
        }

        // You can compute per-item totals here if you want, but API accepts without "Total" in example.
        // We will proceed as-is and return a response-like structure.

        $response = $this->sample()['response'];

        return view('terrace-finance.invoices.index', [
            'result' => [
                'request' => $data,
                'response' => $response,
            ],
            'recentInvoices' => $this->recentInvoices(),
        ]);
    }
}
