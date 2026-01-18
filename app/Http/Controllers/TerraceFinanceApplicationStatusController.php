<?php

namespace App\Http\Controllers;

use App\Models\ApplicationStatusRequest;
use Illuminate\Http\Request;

class TerraceFinanceApplicationStatusController extends Controller
{
    public function index(Request $request)
    {
        $logs = ApplicationStatusRequest::with('result')
            ->orderByDesc('id')
            ->paginate(10);

        $hasQuery = $request->has('ApplicationID');
        $applicationId = (int)($request->query('ApplicationID') ?: 89143);

        // If user submitted a new request (ApplicationID in query), compute + log it
        if ($hasQuery) {
            $result = $this->sampleResult($applicationId);

            $offers = $result['response']['Result']['Offer'] ?? null;
            $offersArr = is_array($offers) ? $offers : [];

            $scenario = $this->scenarioFromApplicationId($applicationId);

            $log = ApplicationStatusRequest::create([
                'ApplicationID' => $applicationId,
                'scenario' => $scenario,
                'status' => 'pending',
            ]);

            $log->result()->create([
                'http_status' => 200,
                'response' => $result['response'],
                'offers' => $offersArr,
            ]);

            $log->status = !empty($result['response']['IsSuccess']) ? 'success' : 'failed';
            $log->save();

            return view('terrace-finance.application-status.index', [
                'result' => $result,
                'offers' => $offersArr,
                'applicationId' => $applicationId,
                'logs' => $logs,
            ]);
        }

        // Otherwise show latest saved record if exists
        $latest = ApplicationStatusRequest::with('result')->latest()->first();

        if ($latest && $latest->result) {
            $response = $latest->result->response ?? [];
            $result = [
                'request' => ['ApplicationID' => $latest->ApplicationID],
                'response' => $response,
            ];

            return view('terrace-finance.application-status.index', [
                'result' => $result,
                'offers' => $latest->result->offers ?? [],
                'applicationId' => $latest->ApplicationID,
                'logs' => $logs,
            ]);
        }

        // Fallback sample display only (no log)
        $result = $this->sampleResult($applicationId);
        $offers = $result['response']['Result']['Offer'] ?? null;

        return view('terrace-finance.application-status.index', [
            'result' => $result,
            'offers' => is_array($offers) ? $offers : [],
            'applicationId' => $applicationId,
            'logs' => $logs,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'ApplicationID' => ['required', 'digits_between:1,10'],
        ]);

        // Redirect to index with query param so it still shows the result,
        // but keeps the submission as POST (prevents duplicates on refresh).
        return redirect()->route('tfc.application-status.index', [
            'ApplicationID' => $data['ApplicationID'],
        ]);
    }


    private function scenarioFromApplicationId(int $applicationId): string
    {
        if ($applicationId === 80741) return 'redirect';
        if ($applicationId === 80766) return 'decline';
        return 'core';
    }

    private function sampleResult(int $applicationId): array
    {
        $request = ['ApplicationID' => $applicationId];

        if ($applicationId === 80741) {
            return ['request' => $request, 'response' => $this->example2Success($applicationId)];
        }

        if ($applicationId === 80766) {
            return ['request' => $request, 'response' => $this->example3Decline($applicationId)];
        }

        return ['request' => $request, 'response' => $this->example1CoreOffer($applicationId)];
    }

    private function baseResponse(): array
    {
        return [
            'IsSuccess' => true,
            'Message' => 'Application Status is available.',
            'Error' => null,
        ];
    }

    private function example1CoreOffer(int $applicationId): array
    {
        $base = $this->baseResponse();

        $base['Result'] = [
            'ApplicationID' => $applicationId,
            'ApplicationStatus' => 'Delivered',
            'ApprovalAmount' => 5000.0000,
            'FundedAmount' => null,
            'FulfilledAmount' => 216.7300,
            'LenderName' => 'Vernance, LLC',
            'LenderStatus' => 'Fulfilled',
            'Offer' => [
                [
                    'BiWeeklyAmount' => 75.04,
                    'BiWeeklyNumPayments' => 48,
                    'MonthlyAmount' => 150.08,
                    'MonthlyNumPayments' => 24,
                    'PriceSheet' => 'core',
                    'PaymentFrequency' => 'BiWeekly',
                    'PaymentAmount' => 75.04,
                    'NumberOfPayments' => 48,
                    'AmountDueAtSigning' => 99.00,
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
                    'NumberOfPayments' => 24,
                    'AmountDueAtSigning' => 99.00,
                    'Promotion' => null,
                    'PromotionPeriod' => 90,
                    'PromotionCost' => 120.00,
                    'MDR' => 8.5,
                    'MDRType' => 'Percent',
                ],
            ],
            'SelectedOffer' => null,
            'SigningRoomURL' => 'https://terracefinance-ux-qa.azurewebsites.net/onlineApplication/signingRoom/example',
            'PricingFactor' => 0.0542,
        ];

        return $base;
    }

    private function example2Success(int $applicationId): array
    {
        $base = $this->baseResponse();

        $base['Result'] = [
            'ApplicationID' => $applicationId,
            'ApplicationStatus' => 'Delivered',
            'ApprovalAmount' => 3000.0,
            'FundedAmount' => null,
            'FulfilledAmount' => null,
            'LenderName' => 'Uown',
            'LenderStatus' => 'Pending Signature',
            'Offer' => [
                [
                    'RedirectUrl' => 'https://origination-sandbox.uownleasing.com/completeApplication?uuid=example&selectedPaymentFrequency=WEEKLY&isBranded=false',
                    'TotalContractAmountWithTax' => 981.45,
                    'TotalContractAmountNoTax' => 923.97,
                    'RegularPaymentWithTax' => 16.82,
                    'NumberOfPayments' => 56,
                    'Frequency' => 'WEEKLY',
                    'FirstPaymentWithFeesAndTax' => 56.82,
                    'FirstPaymentWithFeesNoTax' => 55.79,
                    'FirstPaymentDate' => '2023-06-01',
                    'PaymentDueToday' => 0.0,
                ],
                [
                    'RedirectUrl' => 'https://origination-sandbox.uownleasing.com/completeApplication?uuid=example&selectedPaymentFrequency=BI_WEEKLY&isBranded=false',
                    'TotalContractAmountWithTax' => 981.45,
                    'TotalContractAmountNoTax' => 923.99,
                    'RegularPaymentWithTax' => 33.62,
                    'NumberOfPayments' => 28,
                    'Frequency' => 'BI_WEEKLY',
                    'FirstPaymentWithFeesAndTax' => 73.62,
                    'FirstPaymentWithFeesNoTax' => 71.57,
                    'FirstPaymentDate' => '2023-06-01',
                    'PaymentDueToday' => 0.0,
                ],
            ],
            'SelectedOffer' => 'WEEKLY',
            'SigningRoomURL' => null,
            'PricingFactor' => null,
        ];

        return $base;
    }

    private function example3Decline(int $applicationId): array
    {
        $base = $this->baseResponse();

        $base['Result'] = [
            'ApplicationID' => $applicationId,
            'ApplicationStatus' => 'No Lender',
            'ApprovalAmount' => null,
            'FundedAmount' => null,
            'FulfilledAmount' => null,
            'LenderName' => 'Uown',
            'LenderStatus' => 'Declined',
            'Offer' => null,
            'SelectedOffer' => null,
            'SigningRoomURL' => null,
            'PricingFactor' => null,
        ];

        return $base;
    }
}
