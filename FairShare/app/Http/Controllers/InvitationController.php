<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Colocation;
use App\Models\Invitation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


class InvitationController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function invite(Request $request, Colocation $colocation)
    {
        if (!$colocation->isOwner(Auth::id())) {
            abort(403);
        }

        $request->validate([
            'email' => 'required|email'
        ]);

        $invitation = $colocation->invitations()->create([
            'created_by' => Auth::id(),
            'email' => $request->email,
            'token' => Str::random(40),
            'status' => 'pending'
        ]);

        $url = url('/invitations/' . $invitation->token);

        return back()->with('link', $url);
    }



    public function showInvitation($token)
    {
        $invitation = Invitation::where('token', $token)->firstOrFail();
        $colocation = $invitation->colocation;

        if (! Auth::check() && $invitation->status === 'pending') {
            session(['invitation_token' => $invitation->token]);
        }

        return view('invitations.show', compact('invitation', 'colocation'));
    }


    public function acceptInvitation($token)
    {
        $invitation = Invitation::where('token', $token)->firstOrFail();

        if (Auth::user()->email !== $invitation->email) {
            abort(403);
        }

        $colocation = $invitation->colocation;

        $colocation->users()->attach(Auth::id(), [
            'role' => 'member',
            'joined_at' => now(),
            'left_at' => null
        ]);

        $invitation->update([
            'status' => 'accepted'
        ]);

        return redirect()->route('colocations.show', $colocation);
    }


    public function refuseInvitation($token)
    {
        $invitation = Invitation::where('token', $token)->firstOrFail();

        if (Auth::user()->email !== $invitation->email) {
            abort(403);
        }

        $invitation->update([
            'status' => 'refused',
            'accepted_by' => Auth::id() 
        ]);

        return redirect()->route('dashboard.user')
            ->with('message', 'You have refused the invitation.');
    }




    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
