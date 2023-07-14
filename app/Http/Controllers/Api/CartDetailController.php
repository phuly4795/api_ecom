<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartDetail;
use App\Models\Product;
use Illuminate\Http\Request;

class CartDetailController extends Controller
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
    public function store($request, $data)
    {
        
        $checkCartDetail = CartDetail::where('product_code', $request->product_code)
        ->where('cart_id',  $data->id)
        ->first();

        $product = Product::where('code',$request->product_code)->first();

        if($checkCartDetail){
            $checkCartDetail->update([
                'quatity' => !empty($request->quatity) ?  $checkCartDetail->quatity +  $request->quatity : $checkCartDetail->quatity + 1,
                'updated_by' =>auth()->user()->id
           ]);
        }else{ 
            CartDetail::create([
                'cart_id' => $data->id,
                'product_code' => $request->product_code,
                'quatity' => 1,
                'price' => $product->price,
                'created_by' =>auth()->user()->id
            ]);
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
    public function update(Request $request, $cart, string $productCode)
    {
        $cartDetail = CartDetail::where('cart_id', $cart->id)->where('product_code',$productCode )->update([
            "quatity" => $request->quatity
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
