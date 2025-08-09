<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    
    public function index()
    {
        
        return response()->json(Category::all());
    }
    public function store(Request $request)
    {
        
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        
        $category = Category::create([
            'name' => $request->name
        ]);

        return response()->json($category, 201);
    }

  
    public function show($id)
    {
        $category = Category::findOrFail($id);
        return response()->json($category);
    }

       public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $category = Category::findOrFail($id);
        $category->update([
            'name' => $request->name
        ]);

        return response()->json($category);
    }

   
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return response()->json(['message' => 'Category deleted successfully']);
    }
}
