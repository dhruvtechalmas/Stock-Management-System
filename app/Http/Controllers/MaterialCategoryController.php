<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMaterialCategoryRequest;
use App\Http\Requests\UpdateMaterialCategoryRequest;
use App\Models\MaterialCategory;

class MaterialCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = MaterialCategory::latest()
            ->paginate(25);

        return view('stocks.material-categories.list', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $category = new MaterialCategory();

        return view('stocks.material-categories.create', compact('category'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMaterialCategoryRequest $request)
    {
        MaterialCategory::create($request->validated());

        return redirect()->route('material-category.index')->with([
            'message' => 'Material Category created successfully!',
            'alert-type' => 'success',
        ]);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MaterialCategory $material_category)
    {
        return view('stocks.material-categories.edit', [
            'category' => $material_category
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMaterialCategoryRequest $request, MaterialCategory $material_category)
    {
        $material_category->update($request->validated());

        return redirect()->route('material-category.index')->with([
            'message' => 'Material Category updated successfully!',
            'alert-type' => 'success',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MaterialCategory $material_category)
    {
        // Prevent deletion if materials belong to this category
        if ($material_category->materials()->count() > 0) {
            return redirect()->route('material-category.index')->with([
                'message' => 'Cannot delete this category because materials are assigned to it.',
                'alert-type' => 'error',
            ]);
        }

        $material_category->delete();

        return redirect()->route('material-categories.index')->with([
            'message' => 'Material Category deleted successfully!',
            'alert-type' => 'success',
        ]);
    }
}