<?php

namespace App\Http\Controllers;

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
            'IncludeBankDetails' => 1,
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

    private function offerHistory(): array
    {
        return [
            [
                'ApplicationID' => 75524,
                'Offer' => 'core',
                'BankName' => 'bankTest',
                'AccountType' => 'Checking',
                'ResultUrl' => 'https://terracefinance-ux-qa.azurewebsites.net/6c78f897-d0dd-4d5c-835c-848d137e26e6/signingRoom/cf-offer',
                'CreatedAt' => 'Jan 13, 2026 10:40 AM',
            ],
            [
                'ApplicationID' => 89383,
                'Offer' => 'core',
                'BankName' => '-',
                'AccountType' => '-',
                'ResultUrl' => 'https://terracefinance-ux-qa.azurewebsites.net/xxxx/signingRoom/cf-offer',
                'CreatedAt' => 'Jan 12, 2026 03:05 PM',
            ],
        ];
    }

    public function index()
    {
        return view('terrace-finance.offers.index', [
            'result' => $this->sampleResult(),
            'offerOptions' => $this->offerOptions(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'ApplicationID' => ['required', 'integer'],
            'Offer' => ['required', 'string', 'min:1', 'max:64'],

            'IncludeBankDetails' => ['nullable', 'in:1'],

            'BankDetails.BankName' => ['required_if:IncludeBankDetails,1', 'string', 'min:1', 'max:128'],
            'BankDetails.BankState' => ['nullable', 'string', 'max:2'],
            'BankDetails.AccountNumber' => ['required_if:IncludeBankDetails,1', 'string', 'min:1', 'max:64'],
            'BankDetails.StartDateOfBankAccount' => ['nullable', 'string', 'max:32'],
            'BankDetails.RoutingNumber' => ['required_if:IncludeBankDetails,1', 'digits:9'],
            'BankDetails.AccountType' => ['nullable', 'string', 'max:64'],
        ]);

        // If bank details not included, remove them from payload
        if (($data['IncludeBankDetails'] ?? null) !== '1') {
            unset($data['BankDetails']);
        }

        // Normalize StartDateOfBankAccount to MM/DD/YYYY if provided
        if (!empty($data['BankDetails']['StartDateOfBankAccount'] ?? null)) {
            try {
                $data['BankDetails']['StartDateOfBankAccount'] = Carbon::createFromFormat(
                    'm/d/Y',
                    $data['BankDetails']['StartDateOfBankAccount']
                )->format('m/d/Y');
            } catch (\Throwable $e) {
                return back()->withInput()->withErrors([
                    'BankDetails.StartDateOfBankAccount' => 'StartDateOfBankAccount must be in MM/DD/YYYY format.',
                ]);
            }
        }

        // Sample response structure as documented
        $response = [
            'IsSuccess' => true,
            'Message' => 'Offer saved successfully',
            'Result' => 'https://terracefinance-ux-qa.azurewebsites.net/6c78f897-d0dd-4d5c-835c-848d137e26e6/signingRoom/cf-offer',
        ];

        return view('terrace-finance.offers.index', [
            'result' => ['request' => $data, 'response' => $response],
            'offerOptions' => $this->offerOptions(),
        ]);
    }

    public function history()
    {
        return view('terrace-finance.offers.history', [
            'rows' => $this->offerHistory(),
        ]);
    }
}
