<?php

namespace App\Http\Controllers;

use App\Models\account;
use App\Models\ledger;
use App\Models\products;
use App\Models\purchase;
use App\Models\purchase_details;
use App\Models\purchase_draft;
use App\Models\purchase_receives;
use App\Models\stock;
use App\Models\transactions;
use App\Models\warehouses;
use Illuminate\Database\Console\Migrations\RollbackCommand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class purchaseController extends Controller
{
    public function purchase()
    {
        $vendors = account::where('type', 'Vendor')->get();
        $paidFroms = account::where('type', 'Business')->get();
        $products = products::active()->get();
        $warehouses = warehouses::all();
        return view('purchase.purchase')->with(compact('vendors', 'products', 'paidFroms', 'warehouses'));
    }

    public function StoreDraft(request $req)
    {
        $check = purchase_draft::where('product_id', $req->product)->count();
        if ($check > 0) {
            return "Existing";
        }
        purchase_draft::create(
            [
                'product_id' => $req->product,
                'qty' => $req->qty,
                'rate' => $req->rate,
                'retail' => $req->price,
                'wholesale' => $req->wholesale,
                'warehouseID' => auth()->user()->warehouseID,
            ]
        );

        return "Done";
    }

    public function draftItems()
    {
        $items = purchase_draft::with('product')->where('warehouseID', auth()->user()->warehouseID,)->get();
        return view('purchase.draft')->with(compact('items'));
    }

    public function updateDraftQty($id, $qty)
    {
        $item = purchase_draft::find($id);
        $item->qty = $qty;
        $item->save();

        return "Qty Updated";
    }

    public function updateDraftRate($id, $rate)
    {
        $item = purchase_draft::find($id);
        $item->rate = $rate;
        $item->save();

        return "Rate Updated";
    }

    public function deleteDraft($id)
    {
        purchase_draft::find($id)->delete();
        return "Draft deleted";
    }

    public function storePurchase(request $req)
    {
        $req->validate([
            'date' => 'required',
            'vendor' => 'required',
            'amount' => 'required_if:isPaid,Partial',
            'paidFrom' => 'required_unless:isPaid,No',
        ], [
            'date.required' => 'Select Date',
            'vendor.required' => 'Select Vendor',
            'amount' => 'Enter Paid Amount',
            'paidFrom' => 'Select Account',
        ]);
        try{
            DB::beginTransaction();

        $ref = getRef();
        $vendor = null;
        $walkIn = null;
        $amount = null;
        $paidFrom = null;
        if ($req->isPaid == 'Yes') {
            if ($req->vendor == 0) {
                $walkIn = $req->walkIn;
            } else {
                $vendor = $req->vendor;
            }
            $paidFrom = $req->paidFrom;
        } elseif ($req->isPaid == 'No') {
            $vendor = $req->vendor;
        } else {
            $vendor = $req->vendor;
            $paidFrom = $req->paidFrom;
            $amount = $req->amount;
        }

        $purchase = purchase::create([
            'vendor' => $vendor,
            'paidFrom' => $paidFrom,
            'date' => $req->date,
            'desc' => $req->desc,
            'amount' => $amount,
            'order_number' => $req->ordernumber,
            'warehouseID' => $req->warehouseID,
            'isPaid' => $req->isPaid,
            'ref' => $ref,
            'status' => $req->status,
        ]);
        $desc = "<strong>Purchased</strong><br/> Bill No. " . $purchase->id;
        $items = purchase_draft::all();
        $total = 0;
        $amount1 = 0;
        foreach ($items as $item) {
            $amount1 = $item->rate * $item->qty;
            $total += $amount1;
            purchase_details::create([
                'bill_id' => $purchase->id,
                'product_id' => $item->product_id,
                'rate' => $item->rate,
                'qty' => $item->qty,
                'warehouseID' => $req->warehouseID,
                'date' => $req->date,
                'ref' => $ref,
            ]);
            products::where('id', $item->product_id)->update(
                [
                    'pprice' => $item->rate,
                    'price' => $item->retail,
                    'wholesale' => $item->wholesale,
                ]
            );

           if($req->status == 'Received')
           {
            purchase_receives::create([
                    'purchaseID' => $purchase->id,
                    'productID' => $item->product_id,
                    'date' => $req->date,
                    'qty' => $item->qty,
                    'ref' => $ref,
                ]);
            stock::create([
                'product_id' => $item->product_id,
                'date' => $req->date,
                'desc' => $desc,
                'warehouseID' => $req->warehouseID,
                'cr' => $item->qty,
                'ref' => $ref
            ]);
           }
        }
        $desc1 = "<strong>Products Purchased</strong><br/>Bill No. " . $purchase->id;
        $desc2 = "<strong>Products Purchased</strong><br/>Partial payment of Bill No. " . $purchase->id;
        if ($req->vendor != 0) {
            $check_vendor = account::find($req->vendor);
            if ($req->isPaid == 'Yes') {
                createTransaction($req->paidFrom, $req->date, 0, $total, $desc1, "Purchase", $ref);
                createTransaction($req->vendor, $req->date, $total, $total, $desc1, "Purchase", $ref);
            } elseif ($req->isPaid == 'No') {
                if ($check_vendor->type == "Vendor") {
                    createTransaction($req->vendor, $req->date, $total, 0, $desc1, "Purchase", $ref);
                } else {
                    createTransaction($req->vendor, $req->date, 0, $total, $desc1, "Purchase", $ref);
                }
            } else {
                if ($check_vendor->type == "Vendor") {
                    createTransaction($req->vendor, $req->date, $total, $req->amount, $desc2, "Purchase", $ref);
                } else {
                    createTransaction($req->vendor, $req->date, $req->amount, $total, $desc2, "Purchase", $ref);
                }
                createTransaction($req->paidFrom, $req->date, 0, $req->amount, $desc1, "Purchase", $ref);
            }
        } else {
            createTransaction($req->paidFrom, $req->date, 0, $total, $desc1, "Purchase", $ref);
        }
        $ledger_head = null;
        $ledger_type = null;
        $ledger_details = "Stock Purchased";
        $ledger_amount = null;
        $v_acct = account::find($req->vendor);
        $p_acct = account::find($req->paidFrom);
        if ($req->isPaid == "Yes") {
            if ($req->vendor == 0) {
                $ledger_head = $req->walkIn . "(Walk-In)";
            } else {
                $ledger_head = $v_acct->title;
            }
            $ledger_type = $p_acct->title . "/Paid";
            $ledger_amount = $total;
        } elseif ($req->isPaid == "No") {
            $ledger_head = $v_acct->title;
            $ledger_type = "/Unpaid";
            $ledger_amount = $total;
        } else {
            $ledger_head = $v_acct->title;
            $ledger_type = $p_acct->title . "/Partial";
            $ledger_amount = $req->amount;
        }
        addLedger($req->date, $ledger_head, $ledger_type, $ledger_details, $ledger_amount, $ref);

        DB::commit();
        purchase_draft::truncate();
        return redirect('/purchase/history');
        }
        catch(\Exception $e)
        {
            DB::rollback();
            return $e;
        }

    }
    public function history($from = 0, $to = 0){
        if($from == 0 || $to == 0)
        {
             $from = date('Y-m-d 07:00:s');
            $to = date('Y-m-d H:i:s');
        }
        $history = purchase::with('vendor_account', 'account', 'receives', 'details')
        ->whereBetween('date', [$from, $to])
        ->orderBy('id', 'desc')->get();

        foreach($history as $bill)
        {
            $pendings = [];
            foreach($bill->details as $detail)
            {
                $received = purchase_receives::where('purchaseID', $detail->bill_id)->where('productID', $detail->product_id)->sum('qty');

                $qty = $detail->qty - $received;
                if($qty > 0)
                {
                    $pendings[] = ['qty' => $qty, 'productID' => $detail->product_id, 'productName' => $detail->product->name, 'code' => $detail->product->code, 'ref' => $detail->ref];
                }

            }
            $bill->pendings = $pendings;
        }
        return view('purchase.history')->with(compact('history', 'from', 'to'));
    }

    public function edit($id)
    {
        $bill = purchase::where('id', $id)->first();
        $vendors = account::where('type', '!=', 'Business')->get();
        $paidFroms = account::where('type', 'Business')->get();
        $products = products::active()->get();

        return view('purchase.edit')->with(compact('bill', 'products', 'vendors', 'paidFroms'));
    }

    public function editItems($id)
    {
        $items = purchase_details::with('product')->where('bill_id', $id)->get();

        return view('purchase.edit_details')->with(compact('items'));
    }

    public function editAddItems(request $req, $id)
    {
        $check = purchase_details::where('product_id', $req->product)->where('bill_id', $id)->count();
        if ($check > 0) {
            return "Existing";
        }
        $bill = purchase::where('id', $id)->first();
        $purchase = purchase_details::create(
            [
                'bill_id' => $bill->id,
                'product_id' => $req->product,
                'qty' => $req->qty,
                'rate' => $req->rate,
                'date' => now(),
                'warehouseID' => auth()->user()->warehouseID,
                'ref' => $bill->ref,
            ]
        );

        purchase_receives::where('ref', $bill->ref)->delete();
       
        updatePurchaseAmount($bill->id);
        return "Done";
    }

    public function deleteEdit($id)
    {

        $item = purchase_details::find($id);
        $bill = $item->bill;
        $item->delete();
        stock::where('ref', $bill->ref)->delete();
        purchase_receives::where('ref', $bill->ref)->delete();
        updatePurchaseAmount($bill->id);
        return "Deleted";
    }

    public function updateEditQty($id, $qty)
    {
        $item = purchase_details::find($id);
        $item->qty = $qty;
        $item->save();

        purchase_receives::where('ref', $item->ref)->delete();
        stock::where('ref', $item->ref)->delete();

        updatePurchaseAmount($item->bill->id);
        return "Qty Updated";
    }
    public function updateEditRate($id, $rate)
    {
        $item = purchase_details::find($id);
        $item->rate = $rate;
        $item->save();
        updatePurchaseAmount($item->bill->id);
        return "Rate Updated";
    }

    public function deletePurchase($ref)
    {
        $purchase = purchase::where('ref', $ref)->first();
        foreach($purchase->receives as $receives)
        {
            stock::where('ref', $receives->ref)->delete();
            $receives->delete();
        }

        purchase_details::where('ref', $ref)->delete();
        purchase_receives::where('ref', $ref)->delete();
        transactions::where('ref', $ref)->delete();

        purchase::where('ref', $ref)->delete();

        ledger::where('ref', $ref)->delete();
        session()->forget('confirmed_password');
        return redirect('/purchase/history')->with('error', "Purchase Deleted");
    }

    public function pendings(){
        $history = purchase::with('vendor_account', 'account', 'receives', 'details')
        ->orderBy('id', 'desc')->get();

        foreach($history as $bill)
        {
            $pendings = [];
            foreach($bill->details as $detail)
            {
                $received = purchase_receives::where('purchaseID', $detail->bill_id)->where('productID', $detail->product_id)->sum('qty');

                $qty = $detail->qty - $received;
                if($qty > 0)
                {
                    $pendings[] = ['qty' => $qty, 'productID' => $detail->product_id, 'productName' => $detail->product->name, 'code' => $detail->product->code, 'ref' => $detail->ref];
                }
            }
            $bill->pendings = $pendings;
        }
        return view('purchase.pendings')->with(compact('history'));
    }


}
