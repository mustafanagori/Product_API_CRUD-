<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function orders(Request $request)
    {
        $orders = Order::with('users')->get();
        $title = "Orders";
        // dd($orders);
        // return $orders;
        return view('admin.orders.index', compact('orders','title'));
    }
    public function order_details($id){
        $order = Order::with('users','carts.product')->where("id",$id)->first();
        $title = "Order Details";
        // dd($order);
        return view("admin.orders.details",get_defined_vars());
    }
    public function order_update_status(Request $request){
        $id = $request->itemId;
        $status = $request->status;
        // dd($status);
        $order = Order::find($id);
        $order->status = $status;
        // $email = $order->email;
        // if($order->status == 'Complete'){
        //     $data = [
        //     'orderId' => $id, // You can add more data as needed
        //     ];
        //     Mail::send('admin.email.order_complete_template', $data, function ($message) use ($email) {
        //     $message->to('keharsarfraz90@gmail.com')
        //     ->cc($email)
        //     ->subject('Order Completed');
        //     $message->from('keharjohn@gmail.com', 'eshop online store');
        //     // dd($carts);
        // });
        // }
        $order->save();
        return view("admin.orders.index",get_defined_vars());
    }
    public function userOrders($id){
        // $orders = Cart::where("order_id",$id)->with('product','order')->get();
        $orders = Order::where('user_id',$id)->with('carts')->get();
        foreach ($orders as $order) {
            $cartCount = $order->carts->count();
            $order->cart_count = $cartCount;
            $order->save();
            // Now, $cartCount contains the count of carts for the current order.
            // You can use $cartCount as needed.
        }
        // return $cartCount;
        // return count($orders->carts);
        // dd($orders);
        return $orders;
        // return view("website.user.orders",get_defined_vars());
    }
}
