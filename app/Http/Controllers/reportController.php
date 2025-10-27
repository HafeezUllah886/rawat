<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\products;
use App\Models\stock;
use Illuminate\Http\Request;

class reportController extends Controller
{
    public function stockAlert()
{
    $products = products::all();
    foreach($products as $product)
    {
        $cr = stock::where("product_id", $product->id)->sum('cr');
        $db = stock::where("product_id", $product->id)->sum('db');

        $product->availStock = $cr - $db;
    }
    return view('reports.stock_alert', compact('products'));
}
}
