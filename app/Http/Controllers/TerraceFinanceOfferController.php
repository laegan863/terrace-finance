<?php

namespace App\Http\Controllers;

use App\Models\OfferRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TerraceFinanceOfferController extends Controller
{
    private function offerOptions(): array
    {
        return [
            [
                'Offer' => 'core',
                'PaymentFrequency' => 'BiWeekly',
                'PaymentAmount' => 75.04,
                'NumberOfPayments' => 48,
                'AmountDueAtSigning' => 99.00,
            ],
            [
                'Offer' => 'core',
                'PaymentFrequency' => 'Monthly',
                'PaymentAmount' => 150.08,
                'NumberOfPayments' => 24,
                'AmountDueAtSigning' => 99.00,
            ],
        ];
    }

    private function sampleResult(): array
    {
        $request = [
            'ApplicationID' => 75524,
            'Offer' => 'core',
            'BankDetails' => [
                'BankName' => 'bankTest',
                'BankState' => 'AZ',
                'AccountNumber' => '1233123123',
                'StartDateOfBankAccount' => '11/10/2020',
                'RoutingNumber' => '123123123',
                'AccountType' => 'Checking',
            ],
        ];

        $response = [
            'IsSuccess' => true,
            'Message' => 'Offer saved successfully',
            'Result' => 'https://terracefinance-ux-qa.azurewebsites.net/6c78f897-d0dd-4d5c-835c-848d137e26e6/signingRoom/cf-offer',
        ];

        return compact('request', 'response');
    }

    public function index()
    {
        $logs = OfferRequest::with('result')
            ->orderByDesc('id')
            ->paginate(10);

        $latest = OfferRequest::with('result')->latest()->first();

        if ($latest && $latest->result) {
            $requestData = [
                'ApplicationID' => $latest->ApplicationID,
                'Offer' => $latest->Offer,
            ];

            if (!empty($latest->BankDetails)) {
                $requestData['BankDetails'] = $latest->BankDetails;
            }

            return view('terrace-finance.offers.index', [
                'result' => [
                    'request' => $requestData,
                    'response' => $latest->result->response ?? [],
                ],
                'offerOptions' => $this->offerOptions(),
                'logs' => $logs,
            ]);
        }

        return view('terrace-finance.offers.index', [
            'result' => $this->sampleResult(),
            'offerOptions' => $this->offerOptions(),
            'logs' => $logs,
        ]);
    }


    public function history()
    {
        $logs = OfferRequest::with('result')
            ->orderByDesc('id')
            ->paginate(10);

        return view('terrace-finance.offers.history', [
            'logs' => $logs,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
        'ApplicationID' => ['required', 'integer'],
        'Offer' => ['required', 'string', 'min:1', 'max:64'],

        // Checkbox: when unchecked it's absent, when checked it's "1"
        'IncludeBankDetails' => ['nullable', 'in:1'],

        // BankDetails fields should only be validated if IncludeBankDetails=1
        'BankDetails' => ['nullable', 'array'],
        'BankDetails.BankName' => ['nullable', 'string', 'min:1', 'max:128', 'required_if:IncludeBankDetails,1'],
        'BankDetails.BankState' => ['nullable', 'string', 'max:2'],
        'BankDetails.AccountNumber' => ['nullable', 'string', 'min:1', 'max:64', 'required_if:IncludeBankDetails,1'],
        'BankDetails.StartDateOfBankAccount' => ['nullable', 'string', 'max:32'],
        'BankDetails.RoutingNumber' => ['nullable', 'digits:9', 'required_if:IncludeBankDetails,1'],
        'BankDetails.AccountType' => ['nullable', 'string', 'max:64'],
    ]);


        $bankDetails = null;

        if (($data['IncludeBankDetails'] ?? null) === '1') {
            $bankDetails = $data['BankDetails'] ?? [];

            if (!empty($bankDetails['StartDateOfBankAccount'])) {
                try {
                    $bankDetails['StartDateOfBankAccount'] = Carbon::createFromFormat(
                        'm/d/Y',
                        $bankDetails['StartDateOfBankAccount']
                    )->format('m/d/Y');
                } catch (\Throwable $e) {
                    return back()->withInput()->withErrors([
                        'BankDetails.StartDateOfBankAccount' => 'StartDateOfBankAccount must be in MM/DD/YYYY format.',
                    ]);
                }
            }
        }

        // 1) Save request log
        $offerRequest = OfferRequest::create([
            'ApplicationID' => (int)$data['ApplicationID'],
            'Offer' => $data['Offer'],
            'BankDetails' => $bankDetails,
            'status' => 'pending',
        ]);

        // 2) Response (static for now)
        $httpStatus = 200;
        $response = [
            'IsSuccess' => true,
            'Message' => 'Offer saved successfully',
            'Result' => 'https://terracefinance-ux-qa.azurewebsites.net/6c78f897-d0dd-4d5c-835c-848d137e26e6/signingRoom/cf-offer',
        ];

        // 3) Save result
        $offerRequest->result()->create([
            'http_status' => $httpStatus,
            'response' => $response,
        ]);

        // 4) Update status
        $offerRequest->status = !empty($response['IsSuccess']) ? 'success' : 'failed';
        $offerRequest->save();

        return redirect()->route('tfc.offers.index');
    }
}
