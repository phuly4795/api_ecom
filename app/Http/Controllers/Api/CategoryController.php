<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       try {
            $List = Category::all();

            return response()->json([
                'status' => 201,
                'message' =>"Get data success",
                'data' =>$List,
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
            $validator = Validator::make($request->all(), [
                'code' => 'required|string|unique:categories',
                'name' => 'required|string|max:255',
            ]);
        
            if ($validator->fails()) {
                return response()->json([
                    'error' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 400);
            }
            $CreateCate = Category::create([
                "code" => $request->code,
                "name" => $request->name,
            ]);

            return response()->json([
                'status' => 201,
                'message' =>"add new category success",
                'data' => $CreateCate,
            ], 201);
       } catch (\Throwable $th) {
            return response()->json([
                'status' => 500,
                'message' =>"server error - ". $th,
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
            $DetailCate = Category::findOrFail($id);
            if($DetailCate){
                return response()->json([
                    'status' => 201,
                    'message' =>"get detail category success",
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
            $category = Category::findOrFail($id);
            if($category){
                
                $category->update([
                    "code" => $request->code ?? $category->code,
                    "name" => $request->name ?? $category->name,
                ]);

                return response()->json([
                    'status' => 201,
                    'message' =>"update category success",
                    'data' => $category,
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
            $category = Category::findOrFail($id);
            if($category){
                $category->delete();
                return response()->json([
                    'status' => 201,
                    'message' =>"delete category success",
                    'data' => $category,
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
