<?php

namespace App\Http\Controllers;

use App\Models\InvoiceRequest;
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

    public function index()
    {
        $latest = InvoiceRequest::with('result')->latest()->first();

        if ($latest && $latest->result) {
            $requestData = $latest->only([
                'InvoiceNumber','InvoiceDate','DeliveryDate',
                'ApplicationID','LeadID',
                'Discount','DownPayment','Shipping','Tax',
                'ReturnURL','InvoiceVersion','Items'
            ]);

            $response = $latest->result->response ?? [];
            $resultObj = $response['Result'] ?? [];
            $offers = $resultObj['Offer'] ?? [];

            return view('terrace-finance.invoices.index', [
                'result' => [
                    'request' => $requestData,
                    'response' => $response,
                ],
                'offers' => is_array($offers) ? $offers : [],
            ]);
        }

        $sample = $this->sample();
        $response = $sample['response'];
        $offers = $response['Result']['Offer'] ?? [];

        return view('terrace-finance.invoices.index', [
            'result' => $sample,
            'offers' => $offers,
        ]);
    }

    public function history()
    {
        $logs = InvoiceRequest::with('result')
            ->orderByDesc('id')
            ->paginate(10);

        return view('terrace-finance.invoices.history', [
            'logs' => $logs,
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
            'Items.*.Condition' => ['required', 'in:New,Used,CPO,Refurbished,Parts,Salvage'],
            'Items.*.Price' => ['required', 'numeric'],
            'Items.*.Quantity' => ['required', 'integer', 'min:1'],
            'Items.*.Discount' => ['nullable', 'numeric'],
        ]);

        foreach (['InvoiceDate', 'DeliveryDate'] as $field) {
            try {
                $data[$field] = Carbon::createFromFormat('m-d-Y', $data[$field])->format('m-d-Y');
            } catch (\Throwable $e) {
                return back()->withInput()->withErrors([
                    $field => $field . ' must be in mm-dd-yyyy format.',
                ]);
            }
        }

        if (empty($data['InvoiceVersion'])) {
            $data['InvoiceVersion'] = null;
        }

        // 1) Save request log
        $invoiceRequest = InvoiceRequest::create(array_merge($data, [
            'status' => 'pending',
        ]));

        // 2) Produce response (static for now)
        $httpStatus = 200;
        $response = $this->sample()['response'];

        // 3) Save result
        $invoiceRequest->result()->create([
            'http_status' => $httpStatus,
            'response' => $response,
        ]);

        // 4) Update status
        $invoiceRequest->status = !empty($response['IsSuccess']) ? 'success' : 'failed';
        $invoiceRequest->save();

        // Redirect back to index (clean pagination)
        return redirect()->route('tfc.invoices.index');
    }

}
