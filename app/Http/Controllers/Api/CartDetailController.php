<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartDetail;
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
        $checkCartDetail = CartDetail::where('product_code', $data->product_code)
        ->where('cart_id',  $request->id)
        ->first();
        if($checkCartDetail){
            $checkCartDetail->update([
                'quatity' => $checkCartDetail->quatity +  $data->quatity
            ]);
        }else{
            CartDetail::create([
                'cart_id' => $request->id,
                'product_code' => $data->product_code,
                'quatity' => $data->quatity,
                'price' => $data->price,
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
