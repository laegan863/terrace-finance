<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TerraceFinanceStatusNotificationController extends Controller
{
    private const STORE_FILE = 'tfc/status_notifications.jsonl';

    /**
     * Index: shows latest + recent preview.
     * If there are no stored notifications yet, it will return a sample "latest/recent"
     * for display only (not stored).
     */
    public function index()
    {
        $recent = $this->readRecent(5);
        $latest = $recent[0] ?? null;

        if (!$latest) {
            $samples = $this->displaySamples();
            $latest = $samples[0];
            $recent = $samples;
        }

        $payload = $latest['payload'] ?? null;

        $stats = [
            'total' => $this->countAll(),
            'last_received_at' => $latest['received_at'] ?? '-',
            'last_application_id' => is_array($payload) ? ($payload['ApplicationID'] ?? '-') : '-',
            'last_status' => is_array($payload) ? ($payload['ApplicationStatus'] ?? '-') : '-',
        ];

        return view('terrace-finance.status-notifications.index', [
            'latest' => $latest,
            'recent' => $recent,
            'stats' => $stats,
        ]);
    }

    /**
     * History: shows more stored notifications.
     * If storage is empty, show sample rows for display only.
     */
    public function history()
    {
        $rows = $this->readRecent(200);

        if (empty($rows)) {
            $rows = $this->displaySamples();
        }

        return view('terrace-finance.status-notifications.history', [
            'rows' => $rows,
            'total' => $this->countAll(),
        ]);
    }

    /**
     * Webhook: Terrace Finance calls this endpoint with HTTP POST.
     * Requires a static key in request header.
     * Supports:
     * - Token: <key>
     * - Authorization: Bearer <key>
     * - Authorization: <key>
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

        $payload = $request->all();

        if (!is_array($payload)) {
            return response()->json([
                'isSuccess' => false,
                'message' => 'Invalid payload.',
            ], 400);
        }

        // Must include at least ApplicationID + ApplicationStatus
        if (empty($payload['ApplicationID']) || empty($payload['ApplicationStatus'])) {
            return response()->json([
                'isSuccess' => false,
                'message' => 'ApplicationID and ApplicationStatus are required.',
            ], 400);
        }

        $payload = $this->normalizeNotificationPayload($payload);

        $this->appendNotification([
            'received_at' => now()->format('Y-m-d H:i:s'),
            'source' => 'webhook',
            'headers' => [
                'Token' => $tokenHeader ?: null,
                'Authorization' => $authHeader ?: null,
            ],
            'payload' => $payload,
        ]);

        return response()->json([
            'isSuccess' => true,
            'message' => 'Notification received.',
        ]);
    }

    /**
     * Keep only documented fields used by the notification examples.
     */
    private function normalizeNotificationPayload(array $payload): array
    {
        $normalized = [
            'ApplicationID' => $payload['ApplicationID'] ?? null,
            'LeadID' => $payload['LeadID'] ?? null,
            'InvoiceNumber' => $payload['InvoiceNumber'] ?? '',
            'FundedAmount' => $payload['FundedAmount'] ?? null,
            'ApprovalAmount' => $payload['ApprovalAmount'] ?? null,
            'ApplicationStatus' => $payload['ApplicationStatus'] ?? null,
            'LenderName' => $payload['LenderName'] ?? '',
            'Offer' => $payload['Offer'] ?? null,
            'InvoiceID' => $payload['InvoiceID'] ?? null,
        ];

        // Normalize one common spelling variant if it appears
        if ($normalized['ApplicationStatus'] === 'No Lender') {
            $normalized['ApplicationStatus'] = 'NoLender';
        }

        return $normalized;
    }

    private function appendNotification(array $row): void
    {
        Storage::disk('local')->makeDirectory('tfc');
        $line = json_encode($row, JSON_UNESCAPED_SLASHES) . PHP_EOL;
        Storage::disk('local')->append(self::STORE_FILE, trim($line));
    }

    private function readRecent(int $limit): array
    {
        if (!Storage::disk('local')->exists(self::STORE_FILE)) {
            return [];
        }

        $content = Storage::disk('local')->get(self::STORE_FILE);
        $lines = array_values(array_filter(explode("\n", $content)));

        $lines = array_slice($lines, max(0, count($lines) - $limit));
        $rows = [];

        // newest first
        for ($i = count($lines) - 1; $i >= 0; $i--) {
            $row = json_decode($lines[$i], true);
            if (is_array($row)) {
                $rows[] = $row;
            }
        }

        return $rows;
    }

    private function countAll(): int
    {
        if (!Storage::disk('local')->exists(self::STORE_FILE)) {
            return 0;
        }
        $content = Storage::disk('local')->get(self::STORE_FILE);
        $lines = array_values(array_filter(explode("\n", $content)));
        return count($lines);
    }

    /**
     * Display-only sample rows (not stored). Used when storage is empty.
     */
    private function displaySamples(): array
    {
        $baseHeaders = ['Token' => 'sample'];

        return [
            [
                'received_at' => now()->subMinutes(5)->format('Y-m-d H:i:s'),
                'source' => 'sample',
                'headers' => $baseHeaders,
                'payload' => $this->normalizeNotificationPayload([
                    'ApplicationID' => 1020472,
                    'LeadID' => 356811,
                    'InvoiceNumber' => '3885adcf-2358-4a78-ad16-eb7c5b882239',
                    'FundedAmount' => null,
                    'ApprovalAmount' => 5000.00,
                    'ApplicationStatus' => 'Approved',
                    'LenderName' => 'Vernance, LLC',
                    'Offer' => null,
                    'InvoiceID' => 219718,
                ]),
            ],
            [
                'received_at' => now()->subMinutes(18)->format('Y-m-d H:i:s'),
                'source' => 'sample',
                'headers' => $baseHeaders,
                'payload' => $this->normalizeNotificationPayload([
                    'ApplicationID' => 883820,
                    'LeadID' => 274152,
                    'InvoiceNumber' => '1b071fc2-f748-499f-afc4-43ba31832ce8',
                    'FundedAmount' => 852.74,
                    'ApprovalAmount' => 1500.00,
                    'ApplicationStatus' => 'Funded',
                    'LenderName' => 'Vernance, LLC',
                    'Offer' => null,
                    'InvoiceID' => 159630,
                ]),
            ],
        ];
    }
}
