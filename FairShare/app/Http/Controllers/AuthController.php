<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        $invitationToken = $request->session()->pull('invitation_token');

        if ($invitationToken) {
            $invitation = Invitation::where('token', $invitationToken)->first();

            if ($invitation && $invitation->email === $user->email) {
                $colocation = $invitation->colocation;

                $alreadyMember = $colocation->users()
                    ->where('users.id', $user->id)
                    ->exists();

                if ($alreadyMember) {
                    $colocation->users()->updateExistingPivot($user->id, [
                        'left_at' => null,
                    ]);
                } else {
                    $colocation->users()->attach($user->id, [
                        'role' => 'member',
                        'joined_at' => now(),
                        'left_at' => null,
                    ]);
                }

                if ($invitation->status === 'pending') {
                    $invitation->update([
                        'status' => 'accepted',
                    ]);
                }

                $request->session()->forget('url.intended');

                return redirect()
                    ->route('colocations.show', $colocation)
                    ->with('message', 'Invitation accepted. Welcome to the colocation.');
            }
        }

        return redirect()->intended(route('dashboard'));
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (!Auth::attempt($credentials)) {
            return back()
                ->withErrors('Invalid email or password.')
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }
}
