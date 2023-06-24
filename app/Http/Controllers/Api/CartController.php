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
        //
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
        $cartDetailController = new CartDetailController();
        $createCartDetail = $cartDetailController->store($cart, $request);
    
        $total_price = CartDetail::select('quatity', 'price')->where('cart_id', $cart->id)->get();
     
        foreach($total_price as $item) {
          
            $total += $item->price * $item->quatity;
        }
          
        $cart->update([
            'total_price' => $total
        ]);
    
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
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
