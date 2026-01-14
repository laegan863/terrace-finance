<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

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

    private function sampleResult(): array
    {
        $request = [
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

        // V2 standardized response shape (recommended by the spec)
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
        ]; // :contentReference[oaicite:6]{index=6}

        return compact('request', 'response');
    }

    private function sampleHistory(): array
    {
        return [
            [
                'ApplicationID' => 99044,
                'Applicant' => 'John Doe',
                'ProductInformation' => 'Tire',
                'BestEstimate' => 1500.00,
                'PayFrequency' => 'Monthly',
                'Status' => 'Created',
                'CreatedAt' => 'Jan 13, 2026 09:10 AM',
            ],
            [
                'ApplicationID' => 99012,
                'Applicant' => 'Maria Santos',
                'ProductInformation' => 'Jewelry',
                'BestEstimate' => 2500.00,
                'PayFrequency' => 'BiWeekly',
                'Status' => 'Created',
                'CreatedAt' => 'Jan 12, 2026 04:25 PM',
            ],
            [
                'ApplicationID' => 98988,
                'Applicant' => 'Alex Rivera',
                'ProductInformation' => 'Home',
                'BestEstimate' => 3200.00,
                'PayFrequency' => 'Weekly',
                'Status' => 'Created',
                'CreatedAt' => 'Jan 12, 2026 10:02 AM',
            ],
        ];
    }

    public function index()
    {
        $sample = $this->sampleResult();

        return view('terrace-finance.applications.index', [
            'products' => $this->products(),
            'payFrequencies' => $this->payFrequencies(),
            'result' => $sample,
            'history' => $this->sampleHistory(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'FirstName' => ['required','string','min:1','max:16'],
            'LastName'  => ['required','string','min:1','max:16'],

            'CellNumber' => ['required','digits:10'],

            // These are shown as required fields in the spec (example uses booleans)
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

            // Date fields must be MM/DD/YYYY
            'DOB' => ['required','string','max:10'],
            'LastPayDate' => ['required','string','max:10'],
            'NextPayDate' => ['nullable','string','max:10'],

            // Either GrossIncome or NetIncome must be passed
            'GrossIncome' => ['nullable','numeric','required_without:NetIncome'],
            'NetIncome' => ['nullable','numeric','required_without:GrossIncome'],

            // PayFrequency is case sensitive (enforced by in:)
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

        // Treat as live: return a response shaped like V2.0 standardized response
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
        ]; // :contentReference[oaicite:7]{index=7}

        return view('terrace-finance.applications.index', [
            'products' => $this->products(),
            'payFrequencies' => $this->payFrequencies(),
            'result' => ['request' => $data, 'response' => $response],
            'history' => $this->sampleHistory(),
        ]);
    }
}
