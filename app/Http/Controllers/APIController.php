<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Price;
use App\Models\Stock;
use App\Models\Transaction;

class APIController extends Controller
{

    function stocksList() {
        $stocks = Stock::all();
        foreach ($stocks as $stock) {
            $stock->price = $stock->currentPrice();
        }
        return response()->json($stocks);
    }

    function updateStocks(Request $request) {
        if (!$request->id) { return response()->json(['error'=>'404','msg'=>'Specified stock not found! Please refresh the page.']); }
        $stock = Stock::find($request->id);
        if (!$stock) { return response()->json(['error'=>'404','msg'=>'Specified stock not found! Please refresh the page.']); }
        if (!isset($request->stocks) || $request->stocks<0)  { return response()->json(['error'=>'Invalid Stocks','msg'=>'New Stock input is invalid!']); }
        $stock->stocks = $request->stocks;
        $stock->save();
    }

    function updatePrice(Request $request) {
        if (!$request->id) { return response()->json(['error'=>'404','msg'=>'Specified stock not found! Please refresh the page.']); }
        $stock = Stock::find($request->id);
        if (!$stock) { return response()->json(['error'=>'404','msg'=>'Specified stock not found! Please refresh the page.']); }
        if (!isset($request->price) || $request->price<0)  { return response()->json(['error'=>'Invalid Price','msg'=>'New Price input is invalid!']); }
        $price = new Price();
        $price->stock_id = $stock->id;
        $price->price = $request->price;
        $price->save();
    }

    function transactSales(Request $request) {
        if (!$request->data) { return response()->json(['error'=>'Invalid Data','msg'=>"You've sent an invalid data!"]); }
        if (array_sum($request->data)<=0) { return response()->json(['error'=>'Invalid Data','msg'=>"It seems like your cart is empty..."]); }
        $boughtStocks = [];
        $allStocks = [];
        foreach ($request->data as $key => $value) {
            if ($value) {
                $stock = Stock::find($key);
                if (!$stock) { return response()->json(['error'=>'404','msg'=>'Specified stock not found! Please refresh the page.']); }
                if ($stock->stocks < $value) { return response()->json(['error'=>'Out of Stock','msg'=>$stock->name . ' is out of stock! Please refresh the page.']); }
                $stock->stocks -= $value;
                array_push($allStocks, $stock);
                array_push($boughtStocks, [
                    "name"=>$stock->name,
                    "qty"=>$value,
                    "price"=>$stock->currentPrice() * $value,
                ]);
            }
        }
        \Log::info(\DB::table('transactions')->select(\DB::raw('max(order_number)+1 as a'))->first()->a);
        $order_number = \DB::table('transactions')->select(\DB::raw('max(order_number)+1 as a'))->first()->a;
        $order_number = $order_number?$order_number:1;
        foreach ($allStocks as $stock) {
            $stock->save();
            $transaction = new Transaction();
            $transaction->order_number = $order_number;
            $transaction->stock_id = $stock->id;
            $transaction->qty = $request->data[$stock->id];
            $transaction->save();
        }
        return response()->json([
            "allStocks"=>$allStocks,
            "boughtStocks"=>$boughtStocks,
            "or"=>$order_number
        ]);
    }
}
