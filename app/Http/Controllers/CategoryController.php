<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Category::all());
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'name' => 'required|string|max:100',
        ]);
        $category = Category::create($validate);
        return response()->json($category,201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = Category::find($id);
        if (! $category) {
            return response()->json(["message"=> "Category not found"],404);
        }
        return response()->json($category, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $category = Category::find($id);
        if (! $category) {
            return response()->json(["message"=> "Category not found"],404);
        }
        $validate = $request->validate([
            "name"=> "required|string|max:100",
        ]);
        $category->update($validate);
        return response()->json($category,200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Category::find($id);
        if (! $category) {
            return response()->json(["message"=> "Category not found"],404);
        }
        $category->delete();
        return response()->json(["message"=> "Category deleted successfully"],200);
    }
}