<?php

namespace App\Http\Controllers;

use App\Models\Colocation;
use App\Models\Invitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ColocationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() {}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('colocations.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:30',
            'description' => 'nullable|string|max:100',

        ]);

        $colocation = Colocation::create([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'status' => 'active',
            'invite_code' => Str::random(10)
        ]);

        $colocation->users()->attach(Auth::id(), [
            'role' => 'owner',
            'joined_at' => now(),
            'left_at' => null
        ]);

        return redirect()->route('colocations.show', $colocation);
    }

    /**
     * Display the specified resource.
     */
    public function show(Colocation $colocation)
    {
        $colocation->load([
            'users',
            'expenses.category',
            'categories'
        ]);

        return view('colocations.show', compact('colocation'));
    }

    public function manage(Colocation $colocation)
    {
        if (!$colocation->isOwner(Auth::id())) {
            abort(403);
        }

        $colocation->load('users', 'invitations');

        return view('colocations.manage', compact('colocation'));
    }




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



    public function leave(Colocation $colocation)
    {
        if ($colocation->isOwner(Auth::id())) {
            return back()->withErrors('Owner cannot leave.');
        }

        $colocation->users()->updateExistingPivot(Auth::id(), [
            'left_at' => now()
        ]);

        return redirect()->route('dashboard');
    }

    public function cancel(Colocation $colocation)
    {
        if (!$colocation->isOwner(Auth::id())) {
            abort(403);
        }

        $colocation->update([
            'status' => 'cancelled'
        ]);

        return redirect()->route('dashboard');
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
