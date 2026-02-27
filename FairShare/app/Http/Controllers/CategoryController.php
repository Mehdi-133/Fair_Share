<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Colocation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function store(Request $request, Colocation $colocation)
    {
        $this->authorizeCategoryMutation($colocation);

        $data = $request->validate([
            'name' => [
                'required',
                'string',
                'max:50',
                Rule::unique('categories', 'name'),
            ],
        ]);

        Category::create([
            'name' => $data['name'],
            'colocation_id' => $colocation->id,
        ]);

        return redirect()
            ->route('colocations.manage', $colocation)
            ->with('message', 'Category created successfully.');
    }

    public function update(Request $request, Colocation $colocation, Category $category)
    {
        $this->authorizeCategoryMutation($colocation);
        abort_unless((int) $category->colocation_id === (int) $colocation->id, 404);

        $data = $request->validate([
            'name' => [
                'required',
                'string',
                'max:50',
                Rule::unique('categories', 'name')->ignore($category->id),
            ],
        ]);

        $category->update(['name' => $data['name']]);

        return redirect()
            ->route('colocations.manage', $colocation)
            ->with('message', 'Category updated successfully.');
    }

    public function destroy(Colocation $colocation, Category $category)
    {
        $this->authorizeCategoryMutation($colocation);
        abort_unless((int) $category->colocation_id === (int) $colocation->id, 404);

        $category->delete();

        return redirect()
            ->route('colocations.manage', $colocation)
            ->with('message', 'Category deleted successfully.');
    }

    private function authorizeCategoryMutation(Colocation $colocation): void
    {
        abort_if($colocation->status === 'cancelled', 403);
        abort_unless($colocation->isOwner(Auth::id()), 403);
    }
}
