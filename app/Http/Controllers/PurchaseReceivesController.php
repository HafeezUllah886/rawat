<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\purchase;
use Illuminate\Http\Request;
use App\Models\purchase_receives;
use App\Models\stock;
use Illuminate\Support\Facades\DB;

class PurchaseReceivesController extends Controller
{
    public function store(request $req)
    {
        try{
            DB::beginTransaction();
       $products = $req->product;
       foreach($products as $key => $product)
       {

        $purchase = purchase::find($req->purchaseID);

            purchase_receives::create(
                [
                    'purchaseID' => $req->purchaseID,
                    'productID' => $product,
                    'date' => now(),
                    'qty' => $req->qty[$key],
                    'ref' => $req->ref[$key],
                ]
            );

           stock::create(
            [
                'product_id' => $product,
                'date' => now(),
                'desc' => "Products Received Bill No. $req->purchaseID",
                'cr' => $req->qty[$key],
                'ref' => $req->ref[$key],
                'warehouseID' => $purchase->warehouseID
            ]
           );

       }
       DB::commit();
           return back()->with('success', "Products Received");
    }catch(\Exception $e)
    {
        DB::rollBack();
        return back()->with('error', "Something Went Wrong!");
    }
    }
}
