<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMaterialRequest;
use App\Http\Requests\UpdateMaterialRequest;
use App\Models\Material;
use App\Models\MaterialCategory;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Storage;

class MaterialController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [

            new Middleware('permission:material.index', only: ['index']),

            new Middleware('permission:material.create', only: ['create', 'store']),

            new Middleware('permission:material.view', only: ['show']),

            new Middleware('permission:material.edit', only: ['edit', 'update']),

            new Middleware('permission:material.delete', only: ['destroy']),

        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $materials = Material::latest()
            ->paginate(25);
        $categories = MaterialCategory::where('status', 'Active')->get();

        return view('stocks.materials.list', compact('materials', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $material = new Material;
        $categories = MaterialCategory::where('status', 'Active')->get();

        return view('stocks.materials.create', compact('material', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMaterialRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('materials', 'public');
        }

        Material::create($data);

        return redirect()->route('materials.index')->with([
            'message' => 'Material created successfully!',
            'alert-type' => 'success',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Material $material)
    {
        return view('stocks.materials.view', compact('material'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Material $material)
    {
        return view('stocks.materials.edit', compact('material'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMaterialRequest $request, Material $material)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {

            if ($material->image) {
                Storage::disk('public')->delete($material->image);
            }

            $data['image'] = $request->file('image')->store('materials', 'public');
        }

        $material->update($data);

        return redirect()->route('materials.index')->with([
            'message' => 'Material updated successfully!',
            'alert-type' => 'success',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Material $material)
    {
        $material->delete();

        return redirect()->route('materials.index')->with([
            'message' => 'Material deleted successfully!',
            'alert-type' => 'success',
        ]);
    }
}
