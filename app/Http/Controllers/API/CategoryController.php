<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return response()->json($categories);
    }

    public function store(Request $request)
    {
        $validateData = $request->validate([
            'name' => 'required|unique:categories|max:255',
        ]);

        $category = new Category;
        $category->name = $request->name;

        $category->save();
    }

    public function show($id)
    {
        $category = Category::where('id', $id)->first();
        return response()->json($category);
    }

    public function update(Request $request, $id)
    {
        $data = array();
        $data['name'] =  $request->name;
        Category::where('id', $id)->update($data);
    }

    public function destroy($id)
    {
        Category::where('id', $id)->delete();
    }
}
