<?php

namespace App\Http\Controllers;

use App\Imports\ProductsImport;
use App\Models\catergory;
use App\Models\company;
use App\Models\expense;
use App\Models\products;
use App\Models\purchase_details;
use App\Models\sale;
use App\Models\sale_details;
use App\Models\saleReturn;
use App\Models\saleReturnDetails;
use App\Models\stock;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class productController extends Controller
{

    public function category(){
        $cats = catergory::all();
        return view('products.category')->with(compact('cats'));
    }

    public function storeCat(request $req)
    {
        $check = catergory::where('cat', $req->cat)->count();

        if($check > 0)
        {
            return back()->with('error', 'This category is already exists');
        }
        catergory::create(
            [
                'cat' => $req->cat,
            ]
        );
        return back()->with('success', 'Category has been created');
    }

    public function editCat(request $req){
        $check = catergory::where('cat', $req->cat)->where('id', '!=', $req->id)->count();

        if($check > 0)
        {
            return back()->with('error', 'This category is already exists');
        }
        catergory::where('id', $req->id)->update(
            ['cat' => $req->cat]
        );
        return back()->with('success', 'Category has been Updated');
    }

    public function company(){
        $company = company::all();
        return view('products.company')->with(compact('company'));
    }

    public function storeCoy(request $req)
    {
        $check = company::where('name', $req->name)->count();

        if($check > 0)
        {
            return back()->with('error', 'This company already exists');
        }
        company::create(
            [
                'name' => $req->name,
            ]
        );
        return back()->with('success', 'Company has been created');
    }

    public function editCoy(request $req){
        $check = company::where('name', $req->name)->where('id', '!=', $req->id)->count();

        if($check > 0)
        {
            return back()->with('error', 'This category is already exists');
        }
        company::where('id', $req->id)->update(
            ['name' => $req->name]
        );
        return back()->with('success', 'Company has been Updated');
    }


    public function products(){
        $products = products::all();
        foreach ($products as $product) {
            $stock_cr = stock::where('product_id', $product->id)->sum('cr');
            $stock_db = stock::where('product_id', $product->id)->sum('db');
            $product->stock = $stock_cr - $stock_db;
        }

        return view('products.products')->with(compact('products'));
    }

    public function storePro(request $req)
    {
        $check = products::where('name', $req->name)->where('bike', $req->bike)->where('model', $req->model)->count();

        if($check > 0)
        {
            return back()->with('error', 'This product already exists');
        }
        $image_path1 = null;
        if($req->has('image'))
        {
            $random = rand(1111111111,9999999999);
            $image = $req->file('image');
            $filename = $random . '.' . $image->getClientOriginalExtension();
            $image_path = public_path('images/products/' . $filename);
            $image_path1 = 'images/products/' . $filename;
            $image->move(public_path('images/products/'), $filename);
        }
        products::create(
            [
                'name' => $req->name,
                'code' => $req->code,
                'price' => 0,
                'pprice' => 0,
                'model' => $req->model,
                'bike' => $req->bike,
                'brand' => $req->brand,
                'alert' => $req->alert,
                'pic' =>  $image_path1,
            ]
        );
        return back()->with('success', 'Product has been created');
    }

    public function editPro(request $req){
        $check = products::where('name', $req->name)->where('id', '!=', $req->id)
        ->where('bike', $req->bike)
        ->where('model', $req->model)
        ->count();

        if($check > 0)
        {
            return back()->with('error', 'This Product already exists');
        }
        if($req->hasFile('image')){
            $product = products::find($req->id);
            @unlink(public_path($product->pic));
            $image = $req->file('image');
            $filename = $product->code . '.' . $image->getClientOriginalExtension();
            $image_path = public_path('/images/products/' . $filename);
            $image_path1 = '/images/products/' . $filename;
            $image->move(public_path('/images/products/'), $filename);

        products::where('id', $req->id)->update(
            [
                'name' => $req->name,
                'code' => $req->code,
                'bike' => $req->bike,
                'pic' => $image_path1,
                'brand' => $req->brand,
                'model' => $req->model,
                'alert' => $req->alert,
                'price' => $req->price,
                'wholesale' => $req->wholesale,
            ]
        );
        }
        else
        {
            products::where('id', $req->id)->update(
                [
                    'name' => $req->name,
                    'code' => $req->code,
                    'bike' => $req->bike,
                    'brand' => $req->brand,
                    'model' => $req->model,
                    'alert' => $req->alert,
                    'price' => $req->price,
                    'wholesale' => $req->wholesale,
                ]
            );
        }
        return back()->with('success', 'Product has been Updated');
    }

    public function deletePro($id){
        products::find($id)->delete();
        return back()->with('success', 'Product has been Deleted');
    }

    public function trashedPro(){
        $products = products::onlyTrashed()->get();
        return view('products.trashed')->with(compact('products'));
    }

    public function restorePro($id){
        products::onlyTrashed()->find($id)->restore();
        return back()->with('success', 'Product has been Restored');
    }

    public function profit($from, $to){
    // Assuming $fromDate and $toDate are the provided date range
    $fromDate = $from; // Replace with the actual from date
    $toDate = $to;   // Replace with the actual to date

    $products = Products::all();
    $discounts = sale::whereBetween('date', [$fromDate, $toDate])->sum('discount');
    $expense = expense::whereBetween('date', [$fromDate, $toDate])->sum('amount');
    $returns = saleReturn::whereBetween('date', [$fromDate, $toDate])->get();
    $min_discount = 0;

    foreach ($returns as $return) {
        if ($return->details->sum('qty') == $return->bill->details->sum('qty')) {
            $min_discount += $return->bill->discount;
        }
    }

    foreach ($products as $product) {
        $lastPurchase = purchase_details::where('product_id', $product->id)
            ->where('date', '<=', $toDate)
            ->orderBy('date', 'desc')
            ->first();

        if ($lastPurchase) {
            $lastPurchasePrice = $lastPurchase->rate;
        } else {
            $lastPurchasePrice = 0;
        }

        $purchases = purchase_details::where('product_id', $product->id)
            ->whereBetween('date', [$fromDate, $toDate])->get();
        $sales = sale_details::where('product_id', $product->id)
            ->whereBetween('date', [$fromDate, $toDate])->get();

        $daily_prices = [];
        foreach ($purchases as $purchase) {
            $daily_prices[$purchase->date] = $purchase->rate;
        }
        foreach ($sales as $sale) {
            $daily_prices[$sale->date] = $sale->price;
        }

        $total_purchase_amount = 0;
        $total_purchase_quantity = 0;
        foreach ($purchases as $purchase) {
            $total_purchase_amount += $purchase->qty * $daily_prices[$purchase->date];
            $total_purchase_quantity += $purchase->qty;
        }

        $total_sale_amount = 0;
        $total_sale_quantity = 0;
        foreach ($sales as $sale) {
            $total_sale_amount += $sale->qty * $daily_prices[$sale->date];
            $total_sale_quantity += $sale->qty;
        }

        if ($total_sale_amount == 0) {
            $profit = 0;
        } else {
            $profit = $total_sale_amount - $total_purchase_amount;
        }

        if ($total_purchase_quantity == 0) {
            $average_purchase_price = $lastPurchasePrice;
        } else {
            $average_purchase_price = $total_purchase_amount / $total_purchase_quantity;
        }

        if ($total_sale_quantity == 0) {
            $average_sale_price = 0;
        } else {
            $average_sale_price = $total_sale_amount / $total_sale_quantity;
        }

        $return = saleReturnDetails::where('product_id', $product->id)->count('qty');

        $stock_cr = stock::where('product_id', $product->id)->sum('cr');
        $stock_db = stock::where('product_id', $product->id)->sum('db');
        $available_stock = $stock_cr - $stock_db;

        $product->profit = $profit;
        $product->purchase_quantity = $total_purchase_quantity;
        $product->sale_quantity = $total_sale_quantity;
        $product->average_purchase_price = $average_purchase_price;
        $product->average_sale_price = $average_sale_price;
        $product->ppu = $average_sale_price - $average_purchase_price;
        $product->available_stock = $available_stock;
        $product->return = $return;
    }

    $discounts -= $min_discount;

    return view('products.profit')->with(compact('products', 'discounts', 'expense', 'from', 'to'));
    }

    public function getPro(request $req){
        $pro = products::find($req->id);

        return response()->json(
            [
                'pro' => $pro
            ]
        );
    }

    public function import(request $req)
    {
        $file = $req->file;
        $extension = $file->getClientOriginalExtension();
        if($extension == "xlsx")
        {
            Excel::import(new ProductsImport, $file);
            return back()->with("success", "Successfully imported");
        }
        else
        {
            return back()->with("error", "Invalid file extension");
        }
    }

    public function status($id)
    {
        $product = products::find($id);
        if($product->status == "Active")
        {
            $product->status = "Inactive";
        }
        else
        {
            $product->status = "Active";
        }
        $product->save();
        return back()->with('success', 'Product status has been changed');
    }
}
