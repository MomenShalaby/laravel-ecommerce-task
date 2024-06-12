<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Http\Controllers\Controller;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    use HttpResponses;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = CategoryResource::collection(Category::all());
        return $this->success($categories, 'Categories retrieved', 200);
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryRequest $request)
    {
        $category = Category::create(
            $request->all()
        );
        return $this->success(
            new CategoryResource($category),
            'Category created successfully',
            201
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        $categories = new CategoryResource($category);
        return $this->success($categories, "data is here", 200);

    }


    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryRequest $request, Category $category)
    {
        $category->update(
            $request->all()
        );

        return $this->success(
            new CategoryResource($category),
            'Category created successfully',
            201
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();
        return $this->success('', '', 204);
    }
}
