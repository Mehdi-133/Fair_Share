<?php

namespace App\Http\Controllers\Auth;

use App\Models\Invitation;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        if ($redirect = $this->consumeInvitationToken($request)) {
            return $redirect;
        }

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    private function consumeInvitationToken(Request $request): ?RedirectResponse
    {
        $token = $request->session()->pull('invitation_token');

        if (! $token) {
            return null;
        }

        $invitation = Invitation::where('token', $token)
            ->where('status', 'pending')
            ->first();

        if (! $invitation || $invitation->email !== $request->user()->email) {
            return null;
        }

        $colocation = $invitation->colocation;

        $alreadyMember = $colocation->users()
            ->where('users.id', $request->user()->id)
            ->exists();

        if ($alreadyMember) {
            $colocation->users()->updateExistingPivot($request->user()->id, [
                'left_at' => null,
            ]);
        } else {
            $colocation->users()->attach($request->user()->id, [
                'role' => 'member',
                'joined_at' => now(),
                'left_at' => null,
            ]);
        }

        $invitation->update([
            'status' => 'accepted',
        ]);

        return redirect()
            ->route('colocations.show', $colocation)
            ->with('message', 'Invitation accepted. Welcome to the colocation.');
    }
}
