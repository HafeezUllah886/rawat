<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\products;
use App\Models\stock;
use App\Models\stockTransfer;
use App\Models\warehouses;
use Illuminate\Http\Request;

class StockTransferController extends Controller
{
    public function index()
    {
        $transfers = stockTransfer::orderBy('id', 'desc')->get();
        $warehouses = warehouses::all();
        return view('stock_transfer.index', compact('transfers', 'warehouses'));
    }

    public function create(request $req)
    {
        $from = $req->from;
        $to = $req->to;
        $date = $req->date;
        if($from == $to)
        {
            return back()->with('error', 'Please select different warehouses');
        }
        $products = products::active()->get();

        return view('stock_transfer.create', compact('products', 'from','to', 'date'));
    }

    public function getSingleProduct($id, $warehouse)
    {
        $product = products::find($id);
        $cr = stock::where('product_id', $id)->where('warehouseID', $warehouse)->sum('cr');
        $db = stock::where('product_id', $id)->where('warehouseID', $warehouse)->sum('db');
        $stock = $cr - $db;

        return response()->json(
            [
                'product' => $product,
                'stock' => $stock,
            ]
        );
    }

    public function store(request $req)
    {
        $ids = $req->id;
        $printID = getRef();

        foreach ($ids as $key => $id)
        {
            $ref = getRef();
            $transfer = stockTransfer::create(
                [
                    'productID' => $id,
                    'fromID' => $req->from,
                    'toID' => $req->to,
                    'date' => $req->date,
                    'qty' => $req->qty[$key],
                    'ref' => $ref,
                    'printID' => $printID,
                    'created_by' => auth()->user()->id
                ]
            );

            stock::create(
                [
                    'product_id' => $id,
                    'date' => $req->date,
                    'desc' => "Stock transfered to ".  $transfer->to->name,
                    'db' => $req->qty[$key],
                    'ref' => $ref,
                    'warehouseID' => $req->from
                ]
            );


            stock::create(
                [
                    'product_id' => $id,
                    'date' => $req->date,
                    'desc' => "Stock transfered from ".  $transfer->to->name,
                    'cr' => $req->qty[$key],
                    'ref' => $ref,
                    'warehouseID' => $req->to
                ]
            );
        }

        return redirect('/stocktransfer')->with('success', 'Transfer Created');
    }

    public function delete($ref)
    {
        stockTransfer::where('ref', $ref)->delete();
        stock::where('ref', $ref)->delete();

        return redirect('/stocktransfer')->with('error', 'Transfer Deleted');
    }

    public function print($id)
    {
        $data = stockTransfer::where('printID', $id)->get();
        return view('stock_transfer.print', compact('data'));
    }
}
