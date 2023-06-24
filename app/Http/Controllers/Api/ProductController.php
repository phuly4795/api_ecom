<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       try {
            $List = Product::all();

            $List_new = [];
            foreach($List as $item){
                $List_new[] = [
                    "code"  => $item->code, 
                    "name"  => $item->name, 
                    "price"  => $item->price, 
                    "image"  => $item->image, 
                    "catgory"  => $item->category->name, 
                    "mota"  => $item->mota, 
                ];
            }
            return response()->json([
                'status' => 201,
                'message' =>"Get data success",
                'data' =>$List_new,
            ], 201);
       } catch (\Throwable $th) {
            return response()->json([
                'status' => 500,
                'message' =>"server error",
                'data' =>[],
            ], 500);
       }

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $now = now()->format('dmY'); 
            $validator = Validator::make($request->all(), [
                'code' => 'required|string|unique:products',
                'name' => 'required|string|max:255',
            ]);
        
            if ($validator->fails()) {
                return response()->json([
                    'error' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 400);
            }

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $filename = $now.'-'.$image->getClientOriginalName();
                $image->move(public_path('images'), $filename);
            }

            $CreateProduct = Product::create([
                "code" => $request->code,
                "name" => $request->name,
                "price" => $request->price,
                "mota" => $request->mota,
                "category_id" => $request->category_id,
                "image" => $filename
            ]);

            return response()->json([
                'status' => 201,
                'message' =>"add new Product success",
                'data' => $CreateProduct,
            ], 201);
       } catch (\Throwable $th) {
            return response()->json([
                'status' => 500,
                'message' =>"server error - " . $th,
                'data' =>[],
            ], 500);
       }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {       
            $DetailCate = Product::findOrFail($id);
            if($DetailCate){
                return response()->json([
                    'status' => 201,
                    'message' =>"get detail Product success",
                    'data' => $DetailCate,
                ], 201);
            }else{
                return response()->json([
                    'status' => 400,
                    'message' =>"Id not found",
                    'data' => [],
                ], 400);
            }
           
       } catch (\Throwable $th) {
            return response()->json([
                'status' => 500,
                'message' =>"server error",
                'data' =>[],
            ], 500);
       }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try{
            $Product = Product::findOrFail($id);
            if($Product){
                
                $Product->update([
                    "code" => $request->code ?? $Product->code,
                    "name" => $request->name ?? $Product->name,
                ]);

                return response()->json([
                    'status' => 201,
                    'message' =>"update Product success",
                    'data' => $Product,
                ], 201);
            }else{
                return response()->json([
                    'status' => 400,
                    'message' =>"Id not found",
                    'data' => [],
                ], 400);
            }
        
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 500,
                'message' =>"server error",
                'data' =>[],
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try{
            $Product = Product::findOrFail($id);
            if($Product){
                $Product->delete();
                return response()->json([
                    'status' => 201,
                    'message' =>"delete Product success",
                    'data' => $Product,
                ], 201);
            }else{
                return response()->json([
                    'status' => 400,
                    'message' =>"Id not found",
                    'data' => [],
                ], 400);
            }
        
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 500,
                'message' =>"server error",
                'data' =>[],
            ], 500);
        }
    }
}
