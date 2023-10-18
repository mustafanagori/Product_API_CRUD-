<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\ProductModel;
use Illuminate\Support\Facades\Validator;

class Product extends Controller

{

    public function viewProduct()
    {
    $product = ProductModel::all();
    return $product;
    }

 

    public function addProduct(Request $request) {
    // Define the validation rules for the request data
    $rules = [
        'name' => 'required|string|max:255',
        'product_code' => 'required|string|unique:product,product_code|max:255',
        'description' => 'required|string',
        'price' => 'required|numeric|min:0',
        'discount' => 'required|numeric|min:0|max:100',
        'image' => 'required', // Adjust the allowed file types and size as needed
    ];

    // Validate the request data
    $validator = Validator::make($request->all(), $rules);

    // If validation fails, return the validation errors
    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 400);
    }

    // If validation passes, proceed with saving the product
    $product = new ProductModel();

    // Validate and store the uploaded image
    if ($request->hasFile('image')) {
        $image = $request->file('image');
        $imageName = time().'.'.$image->getClientOriginalExtension();
        $image->move(public_path('images'), $imageName); // Move the uploaded file to a public directory
        $product->image = $imageName; // Save the image filename in the database
    }

    $product->name = $request->input('name');
    $product->product_code = $request->input('product_code');
    $product->description = $request->input('description');
    $product->price = $request->input('price');
    $product->discount = $request->input('discount');

    if ($product->save()) {
        return response()->json(['message' => 'Product added successfully'], 200);
    } else {
        return response()->json(['message' => 'Failed to add a product record'], 500);
    }
}


    public function deleteProduct($id){
        $product = ProductModel::find($id);
        if($product){
            $product->delete();
            return response()->json(['message' => 'product deleted successfully'],200);
        }
        else{
            return response()->json(['message' => 'Failed to delete a product record'], 500);
        }
    }

    
    public function updateProduct(Request $request , $id){

        $rules = [
            'name' => 'string|max:255',
            'product_code' => 'string|unique:product,product_code|max:255',
            'description' => 'string',
            'price' => 'numeric|min:0',
            'discount' => 'numeric|min:0|max:100',
            // Adjust the allowed file types and size as needed
        ];
    
        // Validate the request data
        $validator = Validator::make($request->all(), $rules);
    
        // If validation fails, return the validation errors
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }
        // return $request->all();
        $product = ProductModel::find($id);
        // return $product;
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }
            if($request->name){
                $product->name = $request->name;
            }
            if($request->product_code){
                $product->product_code = $request->product_code;
            }
            if($request->description){
                $product->description = $request->description;
            }
            if($request->price){
                $product->price = $request->price;
            }
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time().'.'.$image->getClientOriginalExtension();
                $image->move(public_path('images'), $imageName);
                $product->image = $imageName;
            }
            if($request->discount){
                $product->discount = $request->discount;
            }
            if ($product->save()) {
            return response()->json(['message' => 'Product updated successfully'], 200);
        } else {
        return response()->json(['message' => 'Failed to update the product'], 500);
    }

    }
}
