<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    public function index()
    {
        return response()->json(Category::all());
    }


   
    public function store(Request $request)
    {
        $validate = $request->validate([
            'name' => 'required|string|max:100',
        ]);
        $category = Category::create($validate);
        return response()->json($category,201);
    }

    
    public function show(string $id)
    {
        $category = Category::find($id);
        if (! $category) {
            return response()->json(["message"=> "Category not found"],404);
        }
        return response()->json($category, 200);
    }

    
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