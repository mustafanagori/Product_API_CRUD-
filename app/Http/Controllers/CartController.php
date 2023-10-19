<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CartController extends Controller
{
    public function cart(Request $request){
        $request->validate([
            'user_id' => 'required',
            'total_amount' => 'required',
        ]);
        $order = new Order();
        $user= User::where('id' ,$request->user_id)->first();
        if ($user) {
            
        $order->user_id = $request->user_id;
        $order->total_amount = $request->total_amount;
        $order->save();
        //Decode the "list" data as JSON
        $list = json_decode($request->list, true);
        if (!is_array($list)) {
            return response()->json(['message' => 'Invalid "list" data format'], 400);
        }
        foreach($list as $item) {
            $product = new Cart();
            $product->order_id = $order->id;
            $product->product_id = $item['product_id'];
            $product->qty = $item['qty'];
            $product->save();
            return response()->json(['message' => "Order placed ",'orderi_d' => $order->id]);
        }
        
    
    }
    else{
        return response()->json(['message' => "user not found can't place an order "]);
    }

    }
}
