<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartDetail;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user()->id;
        $cart = Cart::with('details')->where('user_id', $user)->get();
        return response()->json([
            "status" => "201",
            "message" => "Get data success",
            "data" => $cart
        ], 201);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       try {  
        $total = 0;
        $cart = Cart::where('user_id', auth()->user()->id)->first();
        if($cart){
            $cart->update([
                'user_id' => auth()->user()->id,
                'address' => $request->address,
                'updated_by' => auth()->user()->id
            ]);
        }else{
            $cart = Cart::create([
                'user_id' => auth()->user()->id,
                'address' => $request->address,
                'created_by' => auth()->user()->id
            ]);
        }
        // dd($request,$cart);
        $cartDetailController = new CartDetailController();
        $cartDetailController->store($request, $cart);

        return response()->json([
            'status' => 201,
            'message' => "Add cart success",           
        ], 201);

       } catch (\Throwable $th) {
            return response()->json([
                'status' => 500,
                'message' => "server error ". $th,           
            ], 500);
       }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $productCode)
    {
        $cart = Cart::where('user_id', auth()->user()->id)->first();

        $cartDetailController = new CartDetailController();
        $cartDetailController->update($request, $cart, $productCode);
        return response()->json([
            "status" => 201,
            "message" => "update quatity success",
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $input = $request->all();
        if($input['product_code']){
            $cartDetail = CartDetail::where('cart_id', $input['cart_id'])->where('product_code', $input['product_code'])->delete();
        }
        else{
            $cartDetail = CartDetail::where('cart_id', $input['cart_id'])->delete();
        }
        
        return response()->json([
            "status" => 201,
            "message" => "delete success",
            "data" => $cartDetail
        ],201);
    }
}
