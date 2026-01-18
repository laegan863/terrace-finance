<?php

namespace App\Http\Controllers;

use App\Models\PricingFactorRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TerraceFinancePricingFactorController extends Controller
{
    public function index()
    {
        $logs = PricingFactorRequest::with('result')
            ->orderByDesc('id')
            ->paginate(10);

        $latest = PricingFactorRequest::with('result')->latest()->first();

        if ($latest && $latest->result) {
            $requestData = $latest->only([
                'FirstName','LastName','PhoneNumber','Address','City','State','Zip','Email',
                'SSN','DOB','GrossIncome','ProductInformation','Fingerprint'
            ]);

            return view('terrace-finance.pricing-factor.index', [
                'result' => [
                    'request' => $requestData,
                    'response' => $latest->result->response ?? [],
                ],
                'offers' => $latest->result->offers ?? [],
                'logs' => $logs,
            ]);
        }

        // fallback sample (same as your current)
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
            // your existing offer sample
        ];

        return view('terrace-finance.pricing-factor.index', [
            'result' => [
                'request' => $sampleRequest,
                'response' => $response,
            ],
            'offers' => $offers,
            'logs' => $logs,
        ]);
    }

    public function store(Request $request)
    {
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

            'SSN' => ['nullable','digits:9'],
            'DOB' => ['nullable','string','max:10'],
            'GrossIncome' => ['nullable','numeric'],
            'Fingerprint' => ['nullable','string','max:256'],
        ]);

        if (!empty($data['DOB'])) {
            try {
                $data['DOB'] = Carbon::createFromFormat('m/d/Y', $data['DOB'])->format('m/d/Y');
            } catch (\Throwable $e) {
                return back()->withInput()->withErrors([
                    'DOB' => 'DOB must be in MM/DD/YYYY format.',
                ]);
            }
        }

        // 1) Save request record
        $pfRequest = PricingFactorRequest::create(array_merge($data, [
            'status' => 'pending',
        ]));

        // 2) Produce response (replace later with real API call)
        $httpStatus = 200;

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

        // 3) Save result record
        $pfRequest->result()->create([
            'http_status' => $httpStatus,
            'response' => $response,
            'offers' => $offers,
        ]);

        // 4) Update request status
        $pfRequest->status = !empty($response['IsSuccess']) ? 'success' : 'failed';
        $pfRequest->save();

        // return view('terrace-finance.pricing-factor.index', [
        //     'result' => [
        //         'request' => $data,
        //         'response' => $response,
        //     ],
        //     'offers' => $offers,
        // ]);

        return redirect()->route('tfc.pricing-factor.index');
    }
}
