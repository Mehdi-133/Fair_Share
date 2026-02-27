<?php

namespace App\Http\Controllers;

use App\Models\Colocation;
use App\Models\Expense;
use App\Models\Category;
use App\Services\SettlementCalculator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function __construct(private SettlementCalculator $settlementCalculator)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $colocation = $this->getActiveColocationForUser((int) Auth::id());

        if (! $colocation) {
            return view('expenses.index', [
                'expenses' => collect(),
                'noColocation' => true,
            ]);
        }

        $expenses = Expense::with(['category', 'payer'])
            ->where('colocation_id', $colocation->id)
            ->latest()
            ->get();

        return view('expenses.index', [
            'expenses' => $expenses,
            'noColocation' => false,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $colocation = $this->getActiveColocationForUser((int) Auth::id());

        if (! $colocation) {
            return redirect()
                ->route('expenses.index')
                ->withErrors('You need an active colocation before creating expenses.');
        }

        $colocation->load('categories');

        return view('expenses.create', compact('colocation'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $userId = (int) Auth::id();
        $colocation = $this->getActiveColocationForUser($userId);

        if (! $colocation) {
            return redirect()
                ->route('expenses.index')
                ->withErrors('You need an active colocation before creating expenses.');
        }

        $data = $this->validateExpensePayload($request);

        if (!empty($data['category_id'])) {
            $categoryBelongsToColocation = Category::where('id', $data['category_id'])
                ->where('colocation_id', $colocation->id)
                ->exists();

            abort_unless($categoryBelongsToColocation, 403);
        }

        Expense::create([
            'title' => $data['title'],
            'description' => $data['description'],
            'amount' => $data['amount'],
            'date' => $data['date'],
            'category_id' => $data['category_id'] ?? null,
            'colocation_id' => $colocation->id,
            'payer_id' => $userId,
        ]);
        $this->settlementCalculator->recalculateForColocation($colocation);

        return redirect()
            ->route('expenses.index')
            ->with('success', 'Expense created successfully.');
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
    public function edit(Expense $expense)
    {
        $userId = (int) Auth::id();
        $colocation = $this->getActiveColocationForUser($userId);

        if (! $colocation) {
            return redirect()
                ->route('expenses.index')
                ->withErrors('You do not have an active colocation.');
        }

        abort_unless($expense->colocation_id === $colocation->id, 403);
        abort_unless($expense->payer_id === $userId, 403);

        $colocation->load('categories');

        return view('expenses.edit', compact('expense', 'colocation'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Expense $expense)
    {
        $userId = (int) Auth::id();
        $colocation = $this->getActiveColocationForUser($userId);

        if (! $colocation) {
            return redirect()
                ->route('expenses.index')
                ->withErrors('You do not have an active colocation.');
        }

        abort_unless($expense->colocation_id === $colocation->id, 403);
        abort_unless($expense->payer_id === $userId, 403);

        $data = $this->validateExpensePayload($request);

        if (!empty($data['category_id'])) {
            $categoryBelongsToColocation = Category::where('id', $data['category_id'])
                ->where('colocation_id', $colocation->id)
                ->exists();

            abort_unless($categoryBelongsToColocation, 403);
        }

        $expense->update([
            'title' => $data['title'],
            'description' => $data['description'],
            'amount' => $data['amount'],
            'date' => $data['date'],
            'category_id' => $data['category_id'] ?? null,
        ]);
        $this->settlementCalculator->recalculateForColocation($colocation);

        return redirect()
            ->route('expenses.index')
            ->with('success', 'Expense updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Expense $expense)
    {
        $userId = (int) Auth::id();
        $colocation = $this->getActiveColocationForUser($userId);

        if (! $colocation) {
            return redirect()
                ->route('expenses.index')
                ->withErrors('You do not have an active colocation.');
        }

        abort_unless($expense->colocation_id === $colocation->id, 403);
        abort_unless($expense->payer_id === $userId, 403);

        $expense->delete();
        $this->settlementCalculator->recalculateForColocation($colocation);

        return redirect()
            ->route('expenses.index')
            ->with('success', 'Expense deleted successfully.');
    }

    private function getActiveColocationForUser(int $userId): ?Colocation
    {
        return Colocation::where('status', 'active')
            ->whereHas('users', function ($query) use ($userId) {
                $query->where('users.id', $userId)
                    ->whereNull('colocation_user.left_at');
            })
            ->first();
    }

    private function validateExpensePayload(Request $request): array
    {
        return $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'amount' => 'required|numeric|min:0.01',
            'date' => 'required|date',
            'category_id' => 'nullable|integer|exists:categories,id',
        ]);
    }

}
