<?php

namespace App\Http\Controllers;

use App\Models\Orders;
use App\Models\Books;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class OrdersController extends Controller
{
    public function index()
    {
        $order = Orders::get();

        return response()->json([
            "message" => "Berhasil Tampil order",
            "data" => $order
        ], 200);
    }
    public function show(string $id)
    {
        $order = Orders::with('user_order')->find($id);

        return response()->json([
            "message" => "Berhasil Tampil order",
            "data" => $order
        ], 200);
    }
    public function destroy(string $id)
    {
        $idOrders = Orders::find($id);
        if (!$idOrders) {
            return response([
                "message" => "Data dengan $id tidak ditemukan",
            ], 404);
        }

        $idOrders->delete();
        return response([
            "message" => "berhasil Menghapus Orders"
        ], 200);
    }
    public function update(Request $request, string $id)
    {
        $order = Orders::findOrFail($id);
        $book = Books::findOrFail($order->book_id);

        $stok = $book->stock;
        $qty = $order->quantity;
        // dd($qty);

        $totalQty = ($stok >= $qty) ? $qty : $stok;
        // $auth = base64_encode(config('app.midtrans.server_key') . ":");
        // $res = Http::withHeaders([
        //     "Authorization" => $auth,
        // ])
        //     ->get("https://api.sandbox.midtrans.com/v2/$id/status")
        //     ->json();
        // dd($res);
        // if ($order->status === "success") {
        // }


        // $idOrders = Orders::findOrFail($id);
        $order->status = $request->input('status');
        $order->save();
        if ($order->status === "success") {
            if ($totalQty >= 0){
                $book->stock = $stok - $qty;
                $book->save();
            }
        }
        return response([
            "message" => "Berhasil update status"
        ], 201);
    }
}
