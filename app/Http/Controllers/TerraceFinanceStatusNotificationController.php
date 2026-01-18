<?php

namespace App\Http\Controllers;

use App\Models\StatusNotificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TerraceFinanceStatusNotificationController extends Controller
{
    /**
     * Index: latest + recent + logs (paginated)
     */
    public function index()
    {
        $latest = StatusNotificationRequest::with('result')->latest()->first();

        $recent = StatusNotificationRequest::with('result')
            ->orderByDesc('id')
            ->take(5)
            ->get();

        $logs = StatusNotificationRequest::with('result')
            ->orderByDesc('id')
            ->paginate(10);

        $stats = [
            'total' => StatusNotificationRequest::count(),
            'last_received_at' => $latest ? optional($latest->created_at)->format('Y-m-d H:i') : '-',
            'last_application_id' => $latest?->ApplicationID ?? '-',
            'last_status' => $latest?->ApplicationStatus ?? '-',
        ];

        return view('terrace-finance.status-notifications.index', [
            'latest' => $latest,
            'recent' => $recent,
            'logs' => $logs,
            'stats' => $stats,
        ]);
    }

    /**
     * Manual receive (from UI form).
     * This stores a notification record using the same payload fields.
     */
    public function manualReceive(Request $request)
    {
        $data = $this->validateAndNormalizePayload($request);

        $req = StatusNotificationRequest::create(array_merge($data, [
            'source' => 'manual',
            'token_header' => null,
            'authorization_header' => null,
            'status' => 'pending',
            'raw_payload' => $request->all(),
        ]));

        $response = [
            'isSuccess' => true,
            'message' => 'Notification received.',
        ];

        $req->result()->create([
            'http_status' => 200,
            'response' => $response,
        ]);

        $req->status = 'success';
        $req->save();

        return redirect()->route('tfc.status-notifications.index');
    }

    /**
     * Webhook receive (real live endpoint).
     * Validates static key via Token or Authorization header.
     */
    public function webhook(Request $request)
    {
        $expected = (string) config('services.tfc_status_notification.key', '');

        $tokenHeader = (string) $request->header('Token', '');
        $authHeader = (string) $request->header('Authorization', '');

        $provided = $tokenHeader;

        if ($provided === '' && $authHeader !== '') {
            $provided = preg_match('/^Bearer\s+(.+)$/i', $authHeader, $m) ? trim($m[1]) : trim($authHeader);
        }

        if ($expected !== '' && !hash_equals($expected, $provided)) {
            return response()->json([
                'isSuccess' => false,
                'message' => 'Unauthorized.',
            ], 401);
        }

        $data = $this->validateAndNormalizePayload($request);

        $req = StatusNotificationRequest::create(array_merge($data, [
            'source' => 'webhook',
            'token_header' => $tokenHeader ?: null,
            'authorization_header' => $authHeader ?: null,
            'status' => 'pending',
            'raw_payload' => $request->all(),
        ]));

        $response = [
            'isSuccess' => true,
            'message' => 'Notification received.',
        ];

        $req->result()->create([
            'http_status' => 200,
            'response' => $response,
        ]);

        $req->status = 'success';
        $req->save();

        return response()->json($response, 200);
    }

    /**
     * Validates & normalizes the notification payload fields.
     * - Offer can be null or JSON array/object
     * - Amounts are numeric and limited to avoid DECIMAL overflow
     */
    private function validateAndNormalizePayload(Request $request): array
    {
        // If your DB columns are DECIMAL(12,2), max is 9999999999.99
        // If you change DB to DECIMAL(18,2), max is 9999999999999999.99
        $maxMoney = 9999999999.99;

        $rules = [
            'ApplicationID' => ['required', 'integer'],
            'LeadID' => ['nullable', 'integer'],
            'InvoiceNumber' => ['nullable', 'string', 'max:256'],
            'InvoiceID' => ['nullable', 'integer'],

            'ApprovalAmount' => ['nullable', 'numeric', 'max:' . $maxMoney],
            'FundedAmount' => ['nullable', 'numeric', 'max:' . $maxMoney],

            'ApplicationStatus' => ['required', 'string', 'max:64'],
            'LenderName' => ['nullable', 'string', 'max:128'],

            // Offer is optional; in the form we accept JSON text
            'Offer' => ['nullable', 'string'],
        ];

        $validator = Validator::make($request->all(), $rules);

        // Custom Offer JSON validation (only if provided)
        $validator->after(function ($v) use ($request) {
            $raw = trim((string) $request->input('Offer', ''));

            if ($raw === '') {
                return;
            }

            $decoded = json_decode($raw, true);

            if (!is_array($decoded)) {
                $v->errors()->add('Offer', 'Offer must be valid JSON (array or object) or empty.');
                return;
            }
        });

        $validator->validate();

        $offerRaw = trim((string) $request->input('Offer', ''));
        $offerParsed = null;

        if ($offerRaw !== '') {
            $offerParsed = json_decode($offerRaw, true);
        }

        $applicationStatus = $request->input('ApplicationStatus');
        if ($applicationStatus === 'No Lender') {
            $applicationStatus = 'NoLender';
        }

        return [
            'ApplicationID' => (int) $request->input('ApplicationID'),
            'LeadID' => $request->filled('LeadID') ? (int) $request->input('LeadID') : null,
            'InvoiceNumber' => (string) ($request->input('InvoiceNumber') ?? ''),
            'InvoiceID' => $request->filled('InvoiceID') ? (int) $request->input('InvoiceID') : null,

            'FundedAmount' => $request->filled('FundedAmount') ? (float) $request->input('FundedAmount') : null,
            'ApprovalAmount' => $request->filled('ApprovalAmount') ? (float) $request->input('ApprovalAmount') : null,

            'ApplicationStatus' => (string) $applicationStatus,
            'LenderName' => (string) ($request->input('LenderName') ?? ''),

            'Offer' => $offerParsed,
        ];
    }
}
