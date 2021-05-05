<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PosController extends Controller
{
    public function getproduct($id)
    {
        $products = Product::where('category_id', $id)->get();
        return response()->json($products);
    }

    public function completeorder(Request $request)
    {
        $validatedData = $request->validate([
            'customer_id' => 'required',
            'payment_method' => 'required'
        ]);

        $data = array();
        $data['customer_id'] = $request->customer_id;
        $data['quantity'] = $request->quantity;
        $data['subtotal'] =  $request->subtotal;
        $data['vat'] = $request->vat;
        $data['pay'] = $request->pay;
        $data['due'] = $request->due;
        $data['payment_method'] = $request->payment_method;
        $data['order_date'] = date('d/m/Y');
        $data['order_month'] = date('F');
        $data['order_year'] = date('Y');
        $order_id = DB::table('orders')->insertGetId($data);

        $contents = DB::table('pos')->get();
        $orderData = array();

        foreach ($contents as $content) {
            $orderData['order_id'] = $order_id;
            $orderData['product_id'] = $content->pro_id;
            $orderData['pro_quantity'] = $content->pro_quqantity;
            $orderData['product_price'] = $content->product_price;
            $orderData['sub_total'] = $content->subtotal;
            DB::table('order_details')->insert($orderData);

            DB::table('products')->where('id', $content->pro_id)->update(['product_quantity' => DB::raw('product_quantity - ' . $content->pro_quantity)]);
        }

        DB::table('pos')->delete();
        return response('done');
    }

    public function searchorderdate(Request $request)
    {
        $orderdate = $request->date;
        $newdate = new \DateTime($orderdate);
        $done = $newdate->format('d/m/Y');

        $order = DB::table('orders')->join('customers', 'orders.customer_id', 'customers.id')->select('customers.name', 'orders.*')->where('orders.order_date', $done)->get();

        return response()->json($order);
    }

    public function todaysale()
    {
        $date = date('d/m/Y');
        $sale = DB::table('orders')->where('order_date', $date)->sum('total');
        return response()->json($sale);
    }

    public function todayincome()
    {
        $date = date('d/m/Y');
        $income = DB::table('orders')->where('order_date', $date)->sum('pay');
        return response()->json($income);
    }

    public function totaldue()
    {
        $date = date('d/m/Y');
        $todaydue = DB::table('orders')->where('order_date', $date)->sum('due');
        return response()->json($todaydue);
    }

    public function stockout()
    {
        $products = DB::table('products')->where('product_quantity', '<', '1')->get();
        return response()->json($products);
    }
}
