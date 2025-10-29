<?php

use App\Models\account;
use App\Models\expense;
use App\Models\ledger;
use App\Models\products;
use App\Models\purchase;
use App\Models\purchase_details;
use App\Models\ref;
use App\Models\sale;
use App\Models\sale_details;
use App\Models\stock;
use App\Models\transactions;
use Carbon\Carbon;

function getRef(){
    $ref = ref::first();
    if($ref){
        $ref->ref = $ref->ref + 1;
    }
    else{
        $ref = new ref();
        $ref->ref = 1;
    }
    $ref->save();
    return $ref->ref;
}

function createTransaction($account_id, $date, $cr, $db, $desc, $type, $ref){
    transactions::create(
        [
            'account_id' => $account_id,
            'date' => $date,
            'cr' => $cr,
            'db' => $db,
            'desc' => $desc,
            'warehouseID' => auth()->user()->warehouseID,
            'type' => $type,
            'ref' => $ref,
        ]
    );
}

function getAccountBalance($account_id){
    $transactions  = transactions::where('account_id', $account_id)->get();
    $balance = 0;
    foreach($transactions as $trans)
    {
        $balance += $trans->cr;
        $balance -= $trans->db;
    }

    return $balance;
}

function customerDues(){
   $accounts = account::where('type', 'customer')->get();
   $cr = 0;
   $db = 0;
   $balance = 0;
   foreach ($accounts as $account){
        $cr = transactions::where('account_id', $account->id)->sum('cr');
        $db = transactions::where('account_id', $account->id)->sum('db');

        $balance += $cr - $db;
   }

   return $balance;
}

function vendorDues(){
    $accounts = account::where('type', 'vendor')->get();
    $cr = 0;
    $db = 0;
    $balance = 0;
    foreach ($accounts as $account){
         $cr = transactions::where('account_id', $account->id)->sum('cr');
         $db = transactions::where('account_id', $account->id)->sum('db');

         $balance += $cr - $db;
    }

    return $balance;
 }

function totalCash(){
    $accounts = account::where('Category', 'Cash')->get();
    $cr = 0;
   $db = 0;
   $balance = 0;
   foreach ($accounts as $account){
        $cr = transactions::where('account_id', $account->id)->sum('cr');
        $db = transactions::where('account_id', $account->id)->sum('db');

        $balance += $cr - $db;
   }

   return $balance;

}

function todayCash(){
    $balance = 0;
    $date = date('Y-m-d');
        $transactions = transactions::whereDate('date', $date)->whereHas('account', function ($query) {
            $query->where('Category', 'Cash');
        })->get();

    foreach($transactions as $transaction)
    {
        $balance += $transaction->cr;
        $balance -= $transaction->db;
    }
   return $balance;

}


function totalBank(){
    $accounts = account::where('Category', 'Bank')->get();
    $cr = 0;
   $db = 0;
   $balance = 0;
   foreach ($accounts as $account){
        $cr = transactions::where('account_id', $account->id)->sum('cr');
        $db = transactions::where('account_id', $account->id)->sum('db');

        $balance += $cr - $db;
   }

   return $balance;

}

function todayBank(){
    $accounts = account::where('Category', 'Bank')->get();
    $cr = 0;
   $db = 0;
   $balance = 0;
   $Date = Carbon::now()->format('Y-m-d');
   foreach ($accounts as $account){
        $cr = transactions::where('account_id', $account->id)->whereDate('date', $Date)->sum('cr');
        $db = transactions::where('account_id', $account->id)->whereDate('date', $Date)->sum('db');

        $balance += $cr - $db;
   }

   return $balance;

}

function getPurchaseBillTotal($id){
    $items = purchase_details::where('bill_id', $id)->get();
    $total = 0;
    $amount = 0;
    foreach($items as $item)
    {
        $amount = $item->rate * $item->qty;
        $total += $amount;
    }

    return $total;
}

function getSaleBillTotal($id){
    $items = sale_details::where('bill_id', $id)->get();
    $total = 0;
    $amount = 0;
    foreach($items as $item)
    {
        $amount = ($item->price - $item->discount) * $item->qty;
        $total += $amount;
    }
    $bill = sale::find($id);
    $total = ($total - $bill->discount) + $bill->transport;
    return $total;
}

function updatePurchaseAmount($id){
    $bill = purchase::where('id', $id)->first();
    $total = getPurchaseBillTotal($id);
    if($bill->isPaid == 'No')
    {
        if($bill->vendor_account->type == 'Vendor')
        {
            $trans = transactions::where('account_id', $bill->vendor_account->id)->where('ref', $bill->ref)->get();
            foreach($trans as $tran)
            {
                $tran->cr = $total;
                $tran->date = $bill->date;
                $tran->save();
            }
            
        }
        else{
            $trans = transactions::where('account_id', $bill->vendor_account->id)->where('ref', $bill->ref)->get();
            foreach($trans as $tran)
            {
                $tran->db = $total;
                $tran->date = $bill->date;
                $tran->save();
            }
        }

        $trans->save();
    }
    elseif($bill->isPaid == 'Yes')
    {
        $trans = transactions::where('account_id', $bill->account->id)->where('ref', $bill->ref)->get();
        foreach($trans as $tran)
            {
                $tran->db = $total;
                $tran->date = $bill->date;
                $tran->save();
            }
            $trans = transactions::where('account_id', $bill->vendor_account->id)->where('ref', $bill->ref)->get();
            foreach($trans as $tran)
                {
                    $tran->db = $total;
                    $tran->cr = $total;
                    $tran->date = $bill->date;
                    $tran->save();
                }
    }
    else
    {
        if($bill->vendor_account->type == 'Vendor')
        {
            $trans = transactions::where('account_id', $bill->vendor_account->id)->where('ref', $bill->ref)->get();
            foreach($trans as $tran)
            {
                $tran->cr = $total;
                $tran->date = $bill->date;
                $tran->save();
            }
        }
        else{
            $trans = transactions::where('account_id', $bill->vendor_account->id)->where('ref', $bill->ref)->get();
            foreach($trans as $tran)
            {
                $tran->db = $total;
                $tran->date = $bill->date;
                $tran->save();
            }
        }

    }
}

function updateSaleAmount($id){
    $bill = sale::where('id', $id)->first();
    $total = getSaleBillTotal($id);
    if($bill->isPaid == 'No')
    {
        $trans = transactions::where('account_id', $bill->customer_account->id)->where('ref', $bill->ref)->first();
        $trans->cr = $total;
        $trans->date = $bill->date;
        $trans->save();
    }
    elseif($bill->isPaid == 'Yes')
    {
        $trans = transactions::where('account_id', $bill->account->id)->where('ref', $bill->ref)->first();
        $trans->cr = $total;
        $trans->date = $bill->date;
        $trans->save();
    }
    else
    {
        $trans = transactions::where('account_id', $bill->customer_account->id)->where('ref', $bill->ref)->first();
        $trans->cr = $total;
        $trans->date = $bill->date;
        $trans->save();
    }

    $ledger = ledger::where('ref', $bill->ref)->first();
    $ledger->amount = $total;
    $ledger->save();


}

function todaySale(){
    $Date = Carbon::now()->format('Y-m-d');
    $sales = sale_details::whereDate('date', $Date)->get();

    $total = 0;
    $discount = 0;
    foreach($sales as $item)
    {
        $total += $item->qty * ($item-> price - $item->discount);
        $discount += $item->bill->discount;
    }
    return $total - $discount;
}

function todayPurchase(){
    $Date = Carbon::now()->format('Y-m-d');
    $purchases = purchase_details::whereDate('date', $Date)->get();

    $total = 0;
    foreach($purchases as $item)
    {
        $total += $item->qty * $item->rate;
    }
    return $total;
}

function todayExpense(){
    $Date = Carbon::now()->format('Y-m-d');
    $exp = expense::whereDate('date', $Date)->sum('amount');

    return round($exp,0);
}



function addLedger($date, $head, $type, $details, $amount, $ref){
    ledger::create(
        [
            'date' => $date,
            'head' => $head,
            'type' => $type,
            'details' => $details,
            'warehouseID' => auth()->user()->warehouseID,
            'amount' => $amount,
            'ref' => $ref
        ]
    );

    return "Ledger Added";
}

function deleteLedger($ref)
{
    ledger::where('ref', $ref)->delete();
    return "Ledger Deleted";
}

function cashBook(){
return 000;
}

function previousCash($date){
    $accounts = account::where('Category', 'Cash')->get();
    $cr = 0;
   $db = 0;
   $balance = 0;
   foreach ($accounts as $account){
        $cr = transactions::where('account_id', $account->id)->whereDate('date', '<', $date)->sum('cr');
        $db = transactions::where('account_id', $account->id)->whereDate('date', '<', $date)->sum('db');

        $balance += $cr - $db;
   }

   return $balance;
}
