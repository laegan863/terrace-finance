<?php

namespace App\Http\Controllers;

use App\Models\ApplicationRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TerraceFinanceApplicationController extends Controller
{
    private function products(): array
    {
        return [
            'Automotive','Cell Phone','Dog','Electronics','Home','Jewelry','Landscape Equipment',
            'Livestock Equipment','Medical Equipment','Music Equipment','Personal Product','Pets',
            'Power Sports','Professional Equipment','Recreation','Snow Equipment','Tire','Vehicle','Tool'
        ];
    }

    private function payFrequencies(): array
    {
        return ['Monthly', 'BiWeekly', 'SemiMonthly', 'Weekly'];
    }

    public function index()
    {
        // Paginated logs (latest first)
        $history = ApplicationRequest::with('result')
            ->orderByDesc('id')
            ->paginate(10)
            ->through(function ($log) {
                $resp = $log->result->response ?? [];
                return [
                    'ApplicationID' => $resp['Result'] ?? $log->id,
                    'Applicant' => $log->FirstName . ' ' . $log->LastName,
                    'ProductInformation' => $log->ProductInformation,
                    'BestEstimate' => (float) $log->BestEstimate,
                    'PayFrequency' => $log->PayFrequency,
                    'Status' => $log->status,
                    'CreatedAt' => optional($log->created_at)->format('M d, Y h:i A'),
                ];
            });

        // Latest result shown at the top
        $latest = ApplicationRequest::with('result')->latest()->first();

        if ($latest && $latest->result) {
            $requestData = $latest->only([
                'FirstName','LastName','CellNumber','CellValidation',
                'Address','Address2','City','State','Zip',
                'Email','Fingerprint','Consent','SSN','DOB',
                'GrossIncome','NetIncome','PayFrequency',
                'LastPayDate','NextPayDate','ProductInformation',
                'IdentificationDocumentID','BestEstimate',
            ]);

            return view('terrace-finance.applications.index', [
                'products' => $this->products(),
                'payFrequencies' => $this->payFrequencies(),
                'result' => [
                    'request' => $requestData,
                    'response' => $latest->result->response ?? [],
                ],
                'history' => $history,
            ]);
        }

        // Fallback sample if no logs yet (keeps your page “live”)
        $sampleRequest = [
            'FirstName' => 'John',
            'LastName' => 'Doe',
            'CellNumber' => '5551112222',
            'CellValidation' => true,
            'Address' => '10 Main Street',
            'Address2' => null,
            'City' => 'Ashland',
            'State' => 'MA',
            'Zip' => '01721',
            'Email' => 'testemail@testmerchant.com',
            'Fingerprint' => 'test',
            'Consent' => true,
            'SSN' => '000123456',
            'DOB' => '01/01/1980',
            'GrossIncome' => 4200.00,
            'NetIncome' => 3800.00,
            'PayFrequency' => 'Monthly',
            'LastPayDate' => '08/20/2022',
            'NextPayDate' => '09/06/2022',
            'ProductInformation' => 'Tire',
            'IdentificationDocumentID' => '11223344',
            'BestEstimate' => 1500.00,
        ];

        $response = [
            'IsSuccess' => true,
            'Message' => 'Application created successfully application # 99044',
            'Result' => 99044,
            'Errors' => null,
            'Code' => 200,
            'Error' => null,
            'Token' => null,
            'UserName' => null,
            'Authenticate' => false,
            'RequestId' => 0,
            'Url' => null,
            'PricingFactor' => null,
            'ApprovalAmount' => null,
            'Status' => null,
        ];

        return view('terrace-finance.applications.index', [
            'products' => $this->products(),
            'payFrequencies' => $this->payFrequencies(),
            'result' => [
                'request' => $sampleRequest,
                'response' => $response,
            ],
            'history' => $history,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'FirstName' => ['required','string','min:1','max:16'],
            'LastName'  => ['required','string','min:1','max:16'],

            'CellNumber' => ['required','digits:10'],
            'CellValidation' => ['required','boolean'],
            'Consent' => ['required','boolean'],

            'Address' => ['required','string','min:5','max:100'],
            'Address2' => ['nullable','string','min:2','max:50'],
            'City' => ['required','string','min:1','max:20'],
            'State' => ['required','string','size:2'],
            'Zip' => ['required','digits:5'],
            'Email' => ['required','email','max:50'],

            'Fingerprint' => ['required','string','min:4','max:256'],

            'SSN' => ['required','digits:9'],

            'DOB' => ['required','string','max:10'],
            'LastPayDate' => ['required','string','max:10'],
            'NextPayDate' => ['nullable','string','max:10'],

            'GrossIncome' => ['nullable','numeric','required_without:NetIncome'],
            'NetIncome' => ['nullable','numeric','required_without:GrossIncome'],

            'PayFrequency' => ['required','in:Monthly,BiWeekly,SemiMonthly,Weekly'],

            'ProductInformation' => ['required','string','min:1','max:100'],
            'IdentificationDocumentID' => ['nullable','string','min:1','max:30'],

            'BestEstimate' => ['required','numeric'],
        ]);

        foreach (['DOB', 'LastPayDate', 'NextPayDate'] as $field) {
            if (!empty($data[$field])) {
                try {
                    $data[$field] = Carbon::createFromFormat('m/d/Y', $data[$field])->format('m/d/Y');
                } catch (\Throwable $e) {
                    return back()->withInput()->withErrors([$field => $field . ' must be in MM/DD/YYYY format.']);
                }
            }
        }

        // 1) Save request
        $appRequest = ApplicationRequest::create(array_merge($data, [
            'status' => 'pending',
        ]));

        // 2) Response (replace later with real API call)
        $httpStatus = 200;

        $response = [
            'IsSuccess' => true,
            'Message' => 'Application created successfully application # 99044',
            'Result' => 99044,
            'Errors' => null,
            'Code' => 200,
            'Error' => null,
            'Token' => null,
            'UserName' => null,
            'Authenticate' => false,
            'RequestId' => 0,
            'Url' => null,
            'PricingFactor' => null,
            'ApprovalAmount' => null,
            'Status' => null,
        ];

        // 3) Save result
        $appRequest->result()->create([
            'http_status' => $httpStatus,
            'response' => $response,
        ]);

        // 4) Update status
        $appRequest->status = !empty($response['IsSuccess']) ? 'success' : 'failed';
        $appRequest->save();

        // Redirect back to index so pagination behaves cleanly
        return redirect()->route('tfc.applications.index');
    }
}
