<?php

namespace App\Http\Controllers;

use App\Models\account;
use App\Models\products;
use App\Models\quotation;
use App\Models\quotationDetails;
use Illuminate\Http\Request;

class QuotationController extends Controller
{
    public function quotation(){
        $quots = quotation::with('details')->get();
        $accounts = account::where('type', "!=", "Business")->get();
        return view('quotation.quot')->with(compact('quots', 'accounts'));
    }
    public function storeQuotation(request $req){
        $ref = getRef();
        $customer = $req->customer;
        if($req->customer == "0")
        {
            $customer = null;
        }
        quotation::create([
            'customer' => $customer,
            'walkIn' => $req->walkIn,
            'phone' => $req->phone,
            'address' => $req->address,
            'date' => $req->date,
            'validTill' => $req->valid,
            'warehouseID' => auth()->user()->warehouseID,
            'desc' => $req->desc,
            'ref' => $ref,
        ]);
        return redirect('/quotation/details/'.$ref);
    }
    public function quotDetails($ref)
    {
        $products = products::active()->get();
        $quot = quotation::where('ref', $ref)->first();
        return view('quotation.quot_details')->with(compact('products', 'quot'));
    }

    public function detailsList($ref)
    {
        $items = quotationDetails::where('ref', $ref)->get();
        return view('quotation.list')->with(compact('items'));
    }

    public function storeDetails(request $req)
    {
        $check = quotationDetails::where('product', $req->product)->where('quot', $req->id)->count();
        if($check > 0){
            return "existing";
        }

        quotationDetails::create([
            'quot' => $req->id,
            'product' => $req->product,
            'qty' => $req->qty,
            'price' => $req->price,
            'discount' => $req->discount,
            'ref' => $req->ref,
        ]);

        return "done";
    }

    public function deleteDetails($id){
        quotationDetails::find($id)->delete();
    return back()->with('error', 'Product Deleted');
    }

    public function updateDiscount($ref, $discount){
        quotation::where('ref', $ref)->update([
            'discount' => $discount,
        ]);
    }

    public function print($ref){
        $quot = quotation::with('details', 'customer_account')->where('ref', $ref)->first();

        return view('quotation.print')->with(compact('quot'));
    }

    public function delete($ref){
        quotationDetails::where('ref', $ref)->delete();
        quotation::where('ref', $ref)->delete();
        session()->forget('confirmed_password');
        return redirect('/quotation')->with('error', "Quotation Deleted");
    }


    public function updateQty($id, $qty)
    {
        $item = quotationDetails::find($id);
        $item->qty = $qty;
        $item->save();

        return "Qty Updated";
    }

    public function updateRate($id, $rate, $discount)
    {
        $item = quotationDetails::find($id);
        $item->price = $rate;
        $item->discount = $discount;
        $item->save();

        return "Rate Updated";
    }

}
