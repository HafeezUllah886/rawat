<?php

namespace App\Http\Controllers;

use App\Models\account;
use App\Models\ledger;
use App\Models\products;
use App\Models\purchase_details;
use App\Models\returned_sales;
use App\Models\sale;
use App\Models\sale_details;
use App\Models\sale_draft;
use App\Models\sale_receives;
use App\Models\stock;
use App\Models\transactions;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    public function sale(){
        $customers = account::where('type','Customer')->get();
        $paidIns = account::where('type', 'Business')->get();
        $products = products::active()->get();
        foreach($products as $product)
        {
            $cr = stock::where('product_id', $product->id)->where('warehouseID', auth()->user()->warehouseID)->sum('cr');
            $db = stock::where('product_id', $product->id)->where('warehouseID', auth()->user()->warehouseID)->sum('db');
            $stock = $cr - $db;
            $product->stock = $stock;
        }
        return view('sale.sale')->with(compact('customers', 'products', 'paidIns'));
    }

    public function getPrice($id){
        $product = products::find($id);
        $purchase = purchase_details::where('product_id', $id)->orderBy('id', 'desc')->first();
        $stock = stock::where('product_id', $id)->where('warehouseID', auth()->user()->warehouseID)->get();
        $balance = 0;
        foreach($stock as $item){
            $balance += $item->cr;
            $balance -= $item->db;
        }
        return response()->json(array(
            'balance' => $balance, 'price' => $product->wholesale, 'price1' => $product->price
        ));
    }

    public function StoreDraft(request $req){
        $check = sale_draft::where('product_id', $req->product)->count();
        if($check > 0)
        {
            return "Existing";
        }

        sale_draft::create(
            [
                'product_id' => $req->product,
                'qty' => $req->qty,
                'price' => $req->price,
                'discount' => $req->discount,
                'warehouseID' => auth()->user()->warehouseID,
            ]
        );

        return "Done";
    }

    public function draftItems(){
        $items = sale_draft::with('product')->where('warehouseID', auth()->user()->warehouseID)->get();

        return view('sale.draft')->with(compact('items'));
    }

    public function updateDraftQty($id, $qty){
        $item = sale_draft::find($id);
        $item->qty = $qty;
        $item->save();

        return "Qty Updated";
    }

    public function updateDraftRate($id, $price){
        $item = sale_draft::find($id);
        $item->price = $price;
        $item->save();

        return "Price Updated";
    }
    public function updateDraftDiscount($id, $discount){
        $item = sale_draft::find($id);
        $item->discount = $discount;
        $item->save();

        return "Discount Updated";
    }

    public function deleteDraft($id)
    {
        sale_draft::find($id)->delete();
        return "Draft deleted";
    }

    public function storeSale(request $req){
        $req->validate([
            'date' => 'required',
            'customer' => 'required',
            'amount' => 'required_if:isPaid,Partial',
            'paidIn' => 'required_unless:isPaid,No',
        ],[
            'date.required' => 'Select Date',
            'customer.required' => 'Select customer',
            'amount' => 'Enter Received Amount',
            'paidIn' => 'Select Account',
        ]);
        $ref = getRef();
        $amount = null;
        $paidIn = null;
        if($req->isPaid == 'Yes')
        {
            $paidIn = $req->paidIn;
        }
        elseif($req->isPaid == 'No'){
        }
        else{
            $paidIn = $req->paidIn;
            $amount = $req->amount;
        }

        $sale = sale::create([
            'customer' => $req->customer,
            'paidIn' => $paidIn,
            'date' => $req->date,
            'desc' => $req->desc,
            'amount' => $amount,
            'warehouseID' => auth()->user()->warehouseID,
            'discount' => $req->discount,
            'transport' => $req->transport,
            'dc' => $req->dc,
            'isPaid' => $req->isPaid,
            'ref' => $ref,
            'status' => $req->status,
        ]);

        $desc = "<strong>Sale</strong><br/> Invoice No. ".$sale->id;
        $items = sale_draft::all();
        $total = 0;
        $amount1 = 0;
        foreach ($items as $item){
            $refID = getRef();
            $amount1 = $item->qty * ($item->price - $item->discount);
            $total += $amount1;
            sale_details::create([
                'bill_id' => $sale->id,
                'product_id' => $item->product_id,
                'price' => $item->price,
                'discount' => $item->discount,
                'qty' => $item->qty,
                'warehouseID' => auth()->user()->warehouseID,
                'date' => $req->date,
                'ref' => $refID,
            ]);
            if($req->status == 'Delivered')
           {
            sale_receives::create([
                    'saleID' => $sale->id,
                    'productID' => $item->product_id,
                    'date' => $req->date,
                    'qty' => $item->qty,
                    'ref' => $refID,
                ]);
                stock::create([
                    'product_id' => $item->product_id,
                    'date' => $req->date,
                    'desc' => $desc,
                    'warehouseID' => auth()->user()->warehouseID,
                    'db' => $item->qty,
                    'ref' => $refID
                ]);
           }

         }
         $net_total = $total - $req->discount + $req->transport;
         $desc1 = "<strong>Products Sold</strong><br/>Invoice No. ".$sale->id;
         $desc2 = "<strong>Products Sold</strong><br/>Partial payment of Invoice No. ".$sale->id;
        if($req->customer != 0){

         if($req->isPaid == 'Yes'){
            createTransaction($req->paidIn, $req->date, $net_total, 0, $desc1, "Sale", $ref);
            createTransaction($req->customer, $req->date, $net_total, $net_total, $desc1, "Sale", $ref);
         }
         elseif($req->isPaid == 'No'){
                createTransaction($req->customer, $req->date, $net_total, 0, $desc1, "Sale", $ref);
         }
         else{
            createTransaction($req->customer, $req->date, $net_total, $req->amount, $desc2, "Sale", $ref);
            createTransaction($req->paidIn, $req->date, $req->amount, 0, $desc1, "Sale", $ref);
         }
        }
        else
        {
            createTransaction($req->paidIn, $req->date, $net_total, 0, $desc1, "Sale", $ref);
        }
        $ledger_head = null;
        $ledger_type = null;
        $ledger_details = "Products Sold";
        $ledger_amount = null;
        $c_acct = account::find($req->customer);
        $p_acct = account::find($req->paidIn);
        if($req->isPaid == "Yes"){
            $ledger_head = $c_acct->title;
           $ledger_type = $p_acct->title . "/Paid";
           $ledger_amount = $net_total;
        }
        elseif($req->isPaid == "No")
        {
            $ledger_head = $c_acct->title;
            $ledger_type = "Unpaid";
            $ledger_amount = $net_total;
        }
        else{
            $ledger_head = $c_acct->title;
            $ledger_type = $p_acct->title . "/Partial";
            $ledger_amount = $req->amount;
        }
        addLedger($req->date, $ledger_head, $ledger_type, $ledger_details, $ledger_amount, $ref);

         sale_draft::truncate();

         return redirect('/sale/print/'.$ref.'/sales');
    }
    public function history($from = 0, $to = 0){
        if($from == 0 || $to == 0)
        {
             $from = date('Y-m-d 07:00:s');
            $to = date('Y-m-d H:i:s');
        }

        $history = sale::with('customer_account', 'account')->whereBetween('date', [$from, $to])->where('warehouseID', auth()->user()->warehouseID)->orderBy('id', 'desc')->get();
        foreach($history as $bill)
        {
            $pendings = [];
            foreach($bill->details as $detail)
            {
                $received = sale_receives::where('saleID', $detail->bill_id)->where('productID', $detail->product_id)->sum('qty');

                $qty = $detail->qty - $received;
                if($qty > 0)
                {
                    $pendings[] = ['qty' => $qty, 'productID' => $detail->product_id, 'productName' => $detail->product->name, 'code' => $detail->product->code];
                }

            }
            $bill->pendings = $pendings;
        }

        return view('sale.history')->with(compact('history', 'from', 'to'));
    }

    public function deleteSale($ref)
    {
        $sale = sale::where('ref', $ref)->first();
        foreach($sale->receives as $receives)
        {
            stock::where('ref', $receives->ref)->delete();
            $receives->delete();
        }
       $sale->details()->delete();
        transactions::where('ref', $ref)->delete();
        returned_sales::where('saleID', $sale->id)->delete();
        $sale->delete();
        ledger::where('ref', $ref)->delete();
        session()->forget('confirmed_password');
        return redirect('/sale/history')->with('error', "Sale Deleted");
    }

    public function edit($id)
    {
        $bill = sale::where('id', $id)->first();
        $customer = account::where('type','Customer')->get();
        $paidIn = account::where('type', 'Business')->get();
        $products = products::active()->get();
        foreach($products as $product)
        {
            $cr = stock::where('product_id', $product->id)->where('warehouseID', auth()->user()->warehouseID)->sum('cr');
            $db = stock::where('product_id', $product->id)->where('warehouseID', auth()->user()->warehouseID)->sum('db');
            $stock = $cr - $db;
            $product->stock = $stock;
        }
        return view('sale.edit')->with(compact('bill', 'products', 'customer', 'paidIn'));
    }

    public function editItems($id){
        $items = sale_details::with('product')->where('bill_id', $id)->get();

        return view('sale.edit_details')->with(compact('items'));
    }

    public function editAddItems(request $req, $id){
        $check = sale_details::where('product_id', $req->product)->where('bill_id', $id)->count();
        if($check > 0)
        {
            return "Existing";
        }
        $bill = sale::where('id', $id)->first();
        $date = $bill->date;
        sale_details::create(
            [
                'bill_id' => $bill->id,
                'product_id' => $req->product,
                'qty' => $req->qty,
                'warehouseID' => auth()->user()->warehouseID,
                'price' => $req->price,
                'ref' => $bill->ref,
                'date' => $date,
            ]
        );

        updateSaleAmount($bill->id);
        return "Done";
    }


    public function updateEditQty($id, $qty){

        $item = sale_details::find($id);
        $item->qty = $qty;
        $item->save();

        updateSaleAmount($item->bill->id);
        return "Quantity Updated";
    }

    public function updateEditDiscount($id, $discount){
        $item = sale::find($id);
        $item->discount = $discount;
        $item->save();
        updateSaleAmount($id);
        return "Discount Updated";
    }

    public function updateEditPrice($id, $price){
        $item = sale_details::find($id);
        $item->price = $price;
        $item->save();
        updateSaleAmount($item->bill->id);
        return "Price Updated";
    }

    public function updateEditDiscount1($id, $discount){
        $item = sale_details::find($id);
        $item->discount = $discount;
        $item->save();
        updateSaleAmount($item->bill->id);
        return "Discount Updated";
    }

    public function deleteEdit($id)
    {
        $item = sale_details::find($id);
        $delivers = sale_receives::where('productID', $item->product_id)->where('saleID', $item->bill_id)->first();
        if($delivers)
        {
            stock::where('ref', $delivers->ref)->delete();
            $delivers->delete();
        }
      
        $bill = $item->bill->id;
        $rate = $item->price - $item->discount;
        $amount = $rate * $item->qty;
        returned_sales::create([
            'saleID' => $bill,
            'productID' => $item->product_id,
            'qty' => $item->qty,
            'rate' => $rate,
            'amount' =>  $amount,
            'date' => now(),
            'userID' => auth()->user()->id
        ]);
        $item->delete();
        updateSaleAmount($bill);
        return "Deleted";
    }

    public function print($ref, $target){
        $invoice = sale::with('details', 'customer_account')->where('ref', $ref)->first();
        $details = sale_details::where('bill_id', $invoice->id)->get();
        $previous_bal_cr = transactions::where('account_id', $invoice->customer)->where('date', '<', $invoice->date)->sum('cr');
        $previous_bal_db = transactions::where('account_id', $invoice->customer)->where('date', '<', $invoice->date)->sum('db');
        $prev_balance =$previous_bal_cr - $previous_bal_db;

        $currant_bal_cr = transactions::where('account_id', $invoice->customer)->where('date', '<=', $invoice->date)->sum('cr');
        $currant_bal_db = transactions::where('account_id', $invoice->customer)->where('date', '<=', $invoice->date)->sum('db');

        $cur_balance = $currant_bal_cr - $currant_bal_db;
        return view('sale.print')->with(compact('invoice', 'details', 'prev_balance', 'cur_balance', 'target'));
    }

    public function invprint($ref){
        $invoice = sale::with('details', 'customer_account')->where('ref', $ref)->first();
        $details = sale_details::where('bill_id', $invoice->id)->get();
        $previous_bal_cr = transactions::where('account_id', $invoice->customer)->where('date', '<', $invoice->date)->sum('cr');
        $previous_bal_db = transactions::where('account_id', $invoice->customer)->where('date', '<', $invoice->date)->sum('db');
        $prev_balance =$previous_bal_cr - $previous_bal_db;

        $currant_bal_cr = transactions::where('account_id', $invoice->customer)->where('date', '<=', $invoice->date)->sum('cr');
        $currant_bal_db = transactions::where('account_id', $invoice->customer)->where('date', '<=', $invoice->date)->sum('db');

        $cur_balance = $currant_bal_cr - $currant_bal_db;
        return view('sale.print1')->with(compact('invoice', 'details', 'prev_balance', 'cur_balance'));
    }

    public function printLast()
    {
        $sale = sale::orderBy('id', 'desc')->first();
        if($sale)
        {
            return redirect("sale/print/$sale->ref/pos");
        }

        return redirect('/sale/history')->with('error', "Sale Not Fount");
    }
}
