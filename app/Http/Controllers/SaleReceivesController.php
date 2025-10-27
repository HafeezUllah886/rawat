<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\sale;
use App\Models\sale_receives;
use App\Models\stock;
use Illuminate\Http\Request;

class SaleReceivesController extends Controller
{
    public function store(request $req)
    {
       $products = $req->product;
       foreach($products as $key => $product)
       {

        $ref = getRef();
        $sale = sale::find($req->saleID);
            sale_receives::create(
                [
                    'saleID' => $req->saleID,
                    'productID' => $product,
                    'date' => now(),
                    'qty' => $req->qty[$key],
                    'ref' => $ref,
                ]
            );

           stock::create(
            [
                'product_id' => $product,
                'date' => now(),
                'desc' => "Products Delivered Bill No. $req->saleID",
                'db' => $req->qty[$key],
                'ref' => $ref,
                'warehouseID' => $sale->warehouseID
            ]
           );

       }
       return back()->with('success', "Products Delivered");
    }
}
