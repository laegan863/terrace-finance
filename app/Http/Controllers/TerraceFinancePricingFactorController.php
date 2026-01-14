<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;

class TerraceFinancePricingFactorController extends Controller
{
    public function index()
    {
        $sampleRequest = [
            'FirstName' => 'Axis',
            'LastName' => 'Lead',
            'PhoneNumber' => '6572583078',
            'Address' => 'TestAdd',
            'City' => 'Test',
            'State' => 'CA',
            'Zip' => '90011',
            'Email' => 'applicantexample@gmail.com',
            'SSN' => '666789456',
            'DOB' => '01/25/2001',
            'GrossIncome' => 4200.00,
            'ProductInformation' => 'Jewelry',
            'Fingerprint' => null,
        ];

        $response = [
            'IsSuccess' => true,
            'Message' => 'Success',
            'PricingFactor' => 0.5042,
            'ApprovalAmount' => 5000.00,
            'Status' => 'P',
            'Url' => 'https://terracefinance-ux-qa.azurewebsites.net/onlineApplication/preApplication/503?source=1&identifier=18',
        ];

        $offers = [
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
        ];

        return view('terrace-finance.pricing-factor.index', [
            'result' => [
                'request' => $sampleRequest,
                'response' => $response,
            ],
            'offers' => $offers,
        ]);
    }


    public function store(Request $request)
    {
        // Validate like a real request (basic for now; adjust later from spec)
        $data = $request->validate([
            'FirstName' => ['required','string','max:16'],
            'LastName' => ['required','string','max:16'],
            'PhoneNumber' => ['required','digits:10'],
            'Address' => ['required','string','max:100'],
            'City' => ['required','string','max:20'],
            'State' => ['required','string','size:2'],
            'Zip' => ['required','digits:5'],
            'Email' => ['required','email','max:50'],
            'ProductInformation' => ['required','string','max:100'],

            // optional
            'SSN' => ['nullable','digits:9'],
            'DOB' => ['nullable', 'string', 'max:10'],
            'GrossIncome' => ['nullable','numeric'],
            'Fingerprint' => ['nullable','string','max:256'],
        ]);

        if (!empty($data['DOB'])) {
            try {
                // Expecting MM/DD/YYYY per spec
                $data['DOB'] = Carbon::createFromFormat('m/d/Y', $data['DOB'])->format('m/d/Y');
            } catch (\Throwable $e) {
                return back()->withInput()->withErrors([
                    'DOB' => 'DOB must be in MM/DD/YYYY format.',
                ]);
            }
        }

        // STATIC response for now (later: replace with API call)
        $sampleResponse = [
            'IsSuccess' => true,
            'Message' => 'Static Mode: Pricing Factor computed (sample).',
            'PricingFactor' => 0.5042,
            'ApprovalAmount' => 5000.00,
            'Status' => 'P',
            'Url' => 'https://terracefinance-ux-qa.azurewebsites.net/onlineApplication/preApplication/503?source=1&identifier=18',
        ];

        $offers = [
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
        ];

        // Return same page with result
        return view('terrace-finance.pricing-factor.index', [
            'result' => [
                'request' => $data,
                'response' => $sampleResponse,
            ],
            'offers' => $offers,
        ]);
    }
}
