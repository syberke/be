<?php

namespace App\Http\Controllers;

use App\Models\Orders;
use App\Models\Books;
use App\Models\User;
use Illuminate\Http\Request;
use Midtrans;


class PaymentController extends Controller
{
    public function createTransaction(Request $request)
    {
        // Set konfigurasi Midtrans
        Midtrans\Config::$serverKey = config('app.midtrans.server_key');
        Midtrans\Config::$isProduction = config('app.midtrans.is_production');
        Midtrans\Config::$isSanitized = config('app.midtrans.is_sanitized');
        Midtrans\Config::$is3ds = config('app.midtrans.is_3ds');

        // Membuat order ID unik
        $orderId = uniqid();

        $user = auth()->user();
$userData = User::findOrFail($user->id);

        $book = Books::findOrFail($request->input("idBook"));

        // $stok = $book->stock;
        // $qty = $request->input("quantity");

        // $totalQty = ($stok >= $qty) ? $qty : $stok;
        // if ($totalQty >= 0){
        //     $book->stock = $stok - $qty;
        //     $book->save();
        // }

        // Data transaksi
        $transactionDetails = [
            'order_id' => $orderId,
            'gross_amount' => $book->price * $request->input("quantity"), // Harga total
        ];

        $itemDetails = [
            [
                'id' => $book->id,
                'price' => $book->price,
                'quantity' => $request->input("quantity"),
                'name' => $book->name, //untuk sementara menghindari error
            ],
        ];

        $transaction = [
            'transaction_details' => $transactionDetails,
            'item_details' => $itemDetails,
            // 'customer_details' => $customerDetails,
        ];

        try {
            $snapToken = Midtrans\Snap::getSnapToken($transaction);
            $order = Orders::create([
                'order_id' => $orderId,
                'first_name' => $request->input("first_name"),
                'last_name' => $request->input("last_name"),
                'address' => $request->input("address"),
                'total_price' => $transactionDetails['gross_amount'],
                'quantity' => $request->input("quantity"),
                'status' => 'pending',
                'book_id' => $book->id,
                'user_id' => $userData->id,
            ]);

            return response()->json(['snap_token' => $snapToken, 'order' => $order]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
