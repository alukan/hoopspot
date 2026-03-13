<?php

namespace App\Http\Controllers;

use App\Mail\VerifyEmailMail;
use App\Models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class EmailVerificationController extends Controller
{
    public function notice(Request $request)
    {
        if ($request->user()->email_verified_at) {
            return redirect()->route('home');
        }

        return view('auth.verify-email');
    }

    public function send(Request $request)
    {
        $user = $request->user();

        if ($user->email_verified_at) {
            return redirect()->route('home');
        }

        $token = JWT::encode([
            'sub'   => $user->id,
            'email' => $user->email,
            'exp'   => now()->addHours(24)->timestamp,
        ], config('app.key'), 'HS256');

        Mail::to($user->email)->send(
            new VerifyEmailMail($user, route('verification.verify', ['token' => $token]))
        );

        return back()->with('status', 'Verification link sent!');
    }

    public function verify(string $token)
    {
        try {
            $payload = JWT::decode($token, new Key(config('app.key'), 'HS256'));
        } catch (\Exception $e) {
            return redirect()->route('auth.login')
                ->withErrors(['email' => 'Invalid or expired verification link.']);
        }

        $user = User::find($payload->sub);

        if (! $user || $user->email !== $payload->email) {
            abort(403);
        }

        if (! $user->email_verified_at) {
            $user->email_verified_at = now();
            $user->save();
        }

        Auth::login($user);

        return redirect()->route('home')->with('success', 'Email verified! Welcome to Hoop Spot.');
    }
}
