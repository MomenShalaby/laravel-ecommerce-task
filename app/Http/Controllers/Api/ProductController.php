<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Http\Controllers\Controller;
use App\Traits\CanLoadRelationships;
use App\Traits\FileUploader;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    use HttpResponses;
    use FileUploader;
    use CanLoadRelationships;
    private array $relations = ['category'];
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = $this->loadRelationships(Product::query());

        $products = ProductResource::collection($query->paginate());
        return $this->success($products, 'Products retrieved', 200, true);

    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request)
    {
        $product = Product::create(
            $request->all()
        );
        $query = $this->loadRelationships($product);
        $this->uploadImage($request, $product, "product");

        return $this->success(
            new ProductResource($query),
            'Product created successfully',
            201
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $query = $this->loadRelationships($product);

        $product = new ProductResource($query);
        return $this->success($product, "data is here", 200);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $product->update(
            $request->all()
        );
        $query = $this->loadRelationships($product);

        return $this->success(
            new ProductResource($query),
            'Product created successfully',
            201
        );
    }


    public function updateProductImage(ProductRequest $request, Product $product)
    {
        $this->deleteImage($product->image);
        $this->uploadImage($request, $product, 'product');
        return $this->success($product, "image updated", 202);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return $this->success('', '', 204);

    }
}
