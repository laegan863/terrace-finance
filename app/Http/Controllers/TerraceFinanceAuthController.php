<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TerraceFinanceAuthController extends Controller
{
    public function showLogin()
    {
        return view('terrace-finance.auth.login');
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'UserName' => ['required', 'string'],
            'Password' => ['required', 'string'],
        ]);

        $baseUrl = rtrim(config('terrace_finance_api.base_url'), '/');

        try {
            $response = Http::asForm()
                ->timeout(20)
                ->post($baseUrl . '/api/v1/Authenticate', $data);

            // Works across versions:
            if (!$response->successful()) {
                return back()->withInput()->withErrors([
                    'UserName' => 'API server error. HTTP ' . $response->status() . ' - ' . $response->body(),
                ]);
            }

            // Use object() instead of json() to avoid method issues
            $payload = $response->object(); // stdClass|null

            if (!$payload || empty($payload->IsSuccess) || empty($payload->Token)) {
                return back()->withInput()->withErrors([
                    'UserName' => $payload->Message ?? 'Login failed.',
                ]);
            }

            session([
                'tfc.username' => $data['UserName'],
                'tfc.token' => $payload->Token,
                'tfc.expires_at' => now()->addMinutes(30)->toISOString(),
            ]);

            return redirect()->route('dashboard')->with('success', 'Connected to Terrace Finance API.');
        } catch (\Throwable $e) {
            return back()->withInput()->withErrors([
                'UserName' => 'Request failed: ' . $e->getMessage(),
            ]);
        }
    }


    public function logout(Request $request)
    {
        $request->session()->forget(['tfc.username', 'tfc.token', 'tfc.expires_at']);
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('tfc.login')->with('success', 'Logged out.');
    }
}
