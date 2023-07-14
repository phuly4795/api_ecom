<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartDetail;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
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
            $cart_id = $request->cart_id;
            $cart = Cart::findOrFail($cart_id);
            $autoCode = $this->getAutoOrderCode();
            $total_price = CartDetail::select('quatity', 'price')->where('cart_id', $cart_id)->get();
     
            foreach($total_price as $item) {        
                $total += $item->price * $item->quatity;
            }
            
            $order = Order::create([
                'user_id' => auth()->user()->id,
                'status' => $request->status,
                'code' => $autoCode,
                'cart_id' => $cart_id,
                'total_price' => $total,
                'address' => $cart->address,
                'created_by'         => auth()->user()->id,
            ]);
        
            foreach( $cart->details as $item )
            {
                OrderDetail::create([
                    'order_id'           => $order->id,
                    'product_code'       => $item->product_code,
                    'quatity'            => $item->quatity,
                    'price'              => $item->price,
                    'created_by'         => auth()->user()->id, 
                ]);
            }

            $cart->details->each(function ($detail) {
                $detail->delete();
            });
            $cart->delete();
            
            return response()->json([
                "status" => 201,
                "message" => "Đặt hàng thành công",
                "data" => $order
            ],201);

        } catch (\Throwable $th) {
            return response()->json([
                "status" => 500,
                "message" => "server error ".$th,      
            ],500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(String $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateStatus(Request $request, String $orderCode)
    {
        try {
            $updateStatus = Order::where('code', $orderCode)->first();
            $updateStatus->status = $request->status;
            $updateStatus->save();

            return response()->json([
                "status" => 201,
                "message" => "Cập nhật trạng thái thành công",
                "data" => $updateStatus
            ],201);

        } catch (\Throwable $th) {
            return response()->json([
                "status" => 500,
                "message" => "server error ".$th,
            ],500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(String $id)
    {
        //
    }

    private function getAutoOrderCode()
    {
        $orderType = "DH";
        $y         = date("Y", time());
        $m         = date("m", time());
        $d         = date("d", time());
        $lastCode  = DB::table('orders')->select('code')->where('code', 'like', "$orderType$y$m$d%")->orderBy('id', 'desc')->first();
        $index     = "001";
        if (!empty($lastCode)) {
            $index = (int)substr($lastCode->code, -3);
            $index = str_pad(++$index, 3, "0", STR_PAD_LEFT);
        }
        return "$orderType$y$m$d$index";
    }
}
