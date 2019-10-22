<?php

namespace App\Http\Controllers;

use App\Product;
use App\Http\Requests\ProductCreateRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Http\Resources\Product as ProductResource;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductCreateRequest $request)
    {
        //$product = Product::create($request->all());
        $product = new ProductResource(Product::create([
            "name" => $request->input('data.attributes.name'),
            "price" => $request->input('data.attributes.price')
        ]));

        //return response()->json($product,201);
        return $product;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $product = Product::findOrFail($id);
        return new ProductResource($product);

        //return response()->json($product, 200);

    }
    
    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function showAll()
    {
       // $products = Product::all();
        $products = ProductResource::collection(Product::all());
        return $products;
        //return response()->json($products,200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(ProductUpdateRequest $request, $id)
    {
        //$productUpdate = Product::findOrFail($id);
        //$productUpdate->update($request->all());
        $productUpdate = Product::findOrFail($id);
        $productUpdate->update([
            "name" => $request->input('data.attributes.name'),
            "price" => $request->input('data.attributes.price')
        ]);
        return new ProductResource($productUpdate);

        //return response()->json($productUpdate,200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        $resource = new ProductResource($product);

        return $resource->response()->setStatusCode(204);
}
}
