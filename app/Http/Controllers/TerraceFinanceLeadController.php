<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\LeadResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TerraceFinanceLeadController extends Controller
{   
    public function products()
    {
        return [
            'Automotive','Cell Phone','Dog','Electronics','Home','Jewelry','Landscape Equipment',
            'Livestock Equipment','Medical Equipment','Music Equipment','Personal Product','Pets',
            'Power Sports','Professional Equipment','Recreation','Snow Equipment','Tire','Vehicle','Tool'
        ];
    }

    public function index()
    {
        $authDisabled = config('terrace_finance_api.auth_disabled');

        if (!$authDisabled && (!session('tfc.token') || !session('tfc.username'))) {
            return redirect()->route('tfc.login')
                ->with('auth_error', 'Please login first.');
        }


        $products = $this->products();

        return view('terrace-finance.leads.index', [
            'products' => $products,
            'result' => null,
        ]);
    }

    public function store(Request $request)
    {
        // $authDisabled = config('terrace_finance_api.auth_disabled');

        // if (!$authDisabled && (!session('tfc.token') || !session('tfc.username'))) {
        //     return redirect()->route('tfc.login')
        //         ->with('auth_error', 'Please login first.');
        // }

        // if ($authDisabled) {
        //     return view('terrace-finance.leads.index', [
        //         'products' => $this->products(),
        //         'result' => [
        //             'http_status' => 200,
        //             'api' => [
        //                 'IsSuccess' => true,
        //                 'Message' => 'AUTH DISABLED: Lead submission is in mock mode.',
        //                 'Result' => 'MOCK-LEAD-0001',
        //                 'Url' => null,
        //             ],
        //         ],
        //     ]);
        // }


        $data = $request->validate([
            'FirstName' => ['required','string','min:1','max:16'],
            'LastName'  => ['required','string','min:1','max:16'],
            'PhoneNumber' => ['required','digits:10'],
            'Address'   => ['required','string','min:5','max:100'],
            'City'      => ['required','string','min:1','max:20'],
            'State'     => ['required','string','size:2'],
            'Zip'       => ['required','digits:5'],
            'Email'     => ['required','email','max:50'],
            'Fingerprint' => ['nullable','string','min:4','max:256'],
            'ProductInformation' => ['required','string','min:1','max:100'],
        ]);
        unset($data['_token']);
        $lead = Lead::create([
            'FirstName' => $data['FirstName'],
            'LastName' => $data['LastName'],
            'PhoneNumber' => $data['PhoneNumber'],
            'Address' => $data['Address'],
            'City' => $data['City'],
            'State' => strtoupper($data['State']),
            'Zip' => $data['Zip'],
            'Email' => $data['Email'],
            'Fingerprint' => $data['Fingerprint'] ?? null,
            'ProductInformation' => $data['ProductInformation'],
            'status' => 'pending',
        ]);

        $baseUrl = rtrim(config('terrace_finance_api.base_url'), '/');
        $token = session('tfc.token');
        $username = session('tfc.username');
        $multipart = [
            ['name' => 'FirstName', 'contents' => $data['FirstName']],
            ['name' => 'LastName', 'contents' => $data['LastName']],
            ['name' => 'PhoneNumber', 'contents' => (string)$data['PhoneNumber']],
            ['name' => 'Address', 'contents' => $data['Address']],
            ['name' => 'City', 'contents' => $data['City']],
            ['name' => 'State', 'contents' => strtoupper($data['State'])],
            ['name' => 'Zip', 'contents' => (string)$data['Zip']],
            ['name' => 'Email', 'contents' => $data['Email']],
            ['name' => 'ProductInformation', 'contents' => $data['ProductInformation']],
        ];

        if (!empty($data['Fingerprint'])) {
            $multipart[] = ['name' => 'Fingerprint', 'contents' => $data['Fingerprint']];
        }

        $response = Http::asMultipart()
            ->withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Name' => $username,
            ])
            ->timeout(25)
            ->post($baseUrl . '/api/v1/Lead', $multipart);

        $json = $response->json() ?? [];
        $httpStatus = $response->status();
        $isSuccess = $response->successful() && isset($json['IsSuccess']) && ($json['IsSuccess'] === true || $json['IsSuccess'] === 'true');

        $lead->update([
            'status' => $isSuccess ? 'success' : 'failed',
        ]);

        LeadResult::create([
            'lead_id' => $lead->id,
            'http_status' => $httpStatus,
            'response' => $json,
        ]);

        $products = [
            'Automotive','Cell Phone','Dog','Electronics','Home','Jewelry','Landscape Equipment',
            'Livestock Equipment','Medical Equipment','Music Equipment','Personal Product','Pets',
            'Power Sports','Professional Equipment','Recreation','Snow Equipment','Tire','Vehicle','Tool'
        ];

        return view('terrace-finance.leads.index', [
            'products' => $products,
            'result' => [
                'http_status' => $httpStatus,
                'api' => $json,
                'lead_id' => $lead->id,
                'status' => $lead->status,
            ],
        ]);
    }

}
