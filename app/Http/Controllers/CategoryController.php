<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Enums\Shared\ActiveEnum;
use App\Http\Requests\CategoryRequest;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();

        return view('categories.index', 
            compact('categories')
        );
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(CategoryRequest $request) 
    {
        Category::create($request->validated());

        return redirect()
            ->route('categories.index')
            ->withSuccess($request->message);
    }
    
    public function edit(Category $category)
    {
        $actives = ActiveEnum::cases();

        return view('categories.edit', 
            compact('category', 'actives')
        );
    }

    public function update(CategoryRequest $request, Category $category)  
    {  
        $category->update($request->validated());

        return redirect()
            ->route('categories.index')
            ->withSuccess($request->message);
    }

}
