<?php

namespace App\Http\Controllers;

use App\Imports\customersImport;
use App\Models\account;
use App\Models\deposit;
use App\Models\expense;
use App\Models\ledger;
use App\Models\sale;
use App\Models\transactions;
use App\Models\transfer;
use App\Models\withdraw;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\vendorsImport;

class AccountController extends Controller
{
    public function accounts(){
        $accounts = account::where('type', 'Business')->get();

        return view('finance.accounts')->with(compact('accounts'));
    }

    public function storeAccount(request $req, $type){
        $check = Account::where('title', $req->title)->count();
        if($check > 0){
            return back()->with('error', 'Account already exists');
        }
        if($type == 'Business'){
            $account = account::create(
                [
                    'title' => $req->title,
                    'type' => $type,
                    'category' => $req->cat,
                ]
            );
        }
        else
        {
            $account = account::create(
                [
                    'title' => $req->title,
                    'type' => $type,
                    'phone' => $req->phone,
                    'address' => $req->address,
                ]
            );
        }
        $ref = getRef();
        if($req->amount != 0) {
            createTransaction($account->id, now(), "$req->amount", "0", "Initial Amount", "Initial", $ref);
            addLedger(now(), "Initial Amount", $req->title, "Account Created", $req->amount, $ref);

        }


        return back()->with('success', 'Successfully Created');
    }

    public function editAccount(request $req, $type){

        $check = Account::where('id', '!=', $req->id)->where('title', $req->title)->count();
        if($check > 0){
            return back()->with('error', 'Already exists');
        }
        if($type == 'Business'){
            $account = account::where('id', $req->id)->update(
                [
                    'title' => $req->title,
                    'Category' => $req->cat,
                ]
            );
        }
        else
        {
            $account = account::where('id', $req->id)->update(
                [
                    'title' => $req->title,
                    'phone' => $req->phone,
                    'address' => $req->address,
                ]
            );
        }

        return back()->with('success', 'Successfully Updated');
    }

    public function deleteAccount($id){
        if(transactions::where('account_id', $id)->count() > 0){
            return back()->with('error', 'Unable to delete');
        }

        transactions::where('account_id', $id)->delete();
        account::where('id', $id)->delete();


        return back()->with('success', 'Deleted successfully');
    }

    public function statementView($id, $pdf = false){
        $account = account::with('transactions')->find($id);
        return view('finance.statement')->with(compact('account'));
    }

    public function downloadStatement($id, $from, $to)
    {
        $from = Carbon::createFromFormat('d-m-Y', $from)->format('Y-m-d');
        $to = Carbon::createFromFormat('d-m-Y', $to)->format('Y-m-d');
        $account = Account::with(['transactions' => function ($query) use ($from, $to) {
            $query->whereDate('date', '>=', $from);
            $query->whereDate('date', '<=', $to);
            $query->orderBy('date', 'asc');
        }])->find($id);

        $prev_cr = transactions::where('account_id', $id)->whereDate('date', '<', $from)->sum('cr');
        $prev_db = transactions::where('account_id', $id)->whereDate('date', '<', $from)->sum('db');
        $prev_bal = $prev_cr - $prev_db;

        $cur_bal = getAccountBalance($id);

        $data = $account->toArray();

        $pdf = PDF::loadView('finance.statementPDF', compact('data', 'prev_bal', 'cur_bal', 'from', 'to', 'account'));
        $file_name = $account->title." - Statement.pdf";
        return $pdf->stream($file_name);
    }

    public function details($id, $from, $to)
    {
        $from = Carbon::createFromFormat('d-m-Y', $from)->format('Y-m-d');
        $to = Carbon::createFromFormat('d-m-Y', $to)->format('Y-m-d');
        $items = transactions::where('account_id', $id)->whereDate('date', '>=', $from)->whereDate('date', '<=', $to)->orderBy('date', 'asc')->get();
        $prev = transactions::where('account_id', $id)->where('date', '<', $from)->get();

        $p_balance = 0;
        foreach ($prev as $item) {
            $p_balance += $item->cr;
            $p_balance -= $item->db;
        }

        $all = transactions::where('account_id', $id)->get();
        $account = account::find($id);

        return view('finance.statement_details')->with(compact('items', 'p_balance', 'id', 'account'));
    }

    public function deposit(){
        $deposits = deposit::orderBy('id', 'desc')->get();
        $accounts = account::all();
        return view('finance.deposits')->with(compact('deposits', 'accounts'));
    }

    public function storeDeposit(request $req){
        $ref = getRef();
        $desc = "<strong>Deposit</strong><br>" . $req->desc;
        deposit::create(
            [
                'account_id' => $req->account,
                'date' => $req->date,
                'amount' => $req->amount,
                'desc' => $req->desc,
                'ref' => $ref,
            ]
        );
        $title = account::find($req->account);
        createTransaction($req->account, $req->date, $req->amount, 0, $desc, "Deposit", $ref);
        addLedger($req->date, "Deposit", $title->title, "Amount Deposited", $req->amount, $ref);
        return back()->with('success', 'Amount deposit was successfull');
    }

    public function deleteDeposit($ref)
    {
        deposit::where('ref', $ref)->delete();
        transactions::where('ref', $ref)->delete();
        ledger::where('ref', $ref)->delete();
        session()->forget('confirmed_password');
        return redirect('/deposit')->with('error', "Deposit Deleted");
    }


    public function withdraw(){
        $withdraws = withdraw::orderBy('id', 'desc')->get();
        $accounts = account::all();
        return view('finance.withdraws')->with(compact('withdraws', 'accounts'));
    }

    public function storeWithdraw(request $req){
        $ref = getRef();
        $desc = "<strong>Withdraw</strong><br>" . $req->desc;
        withdraw::create(
            [
                'account_id' => $req->account,
                'date' => $req->date,
                'amount' => $req->amount,
                'desc' => $req->desc,
                'ref' => $ref,
            ]
        );
        $title = account::find($req->account);
        createTransaction($req->account, $req->date, 0, $req->amount, $desc, 'Withdraw', $ref);
        addLedger($req->date, "Withdraw", $title->title, "Amount Withdrawn", $req->amount, $ref);
        return back()->with('success', 'Amount withdraw was successfull');
    }

    public function deleteWithdraw($ref)
    {
        withdraw::where('ref', $ref)->delete();
        transactions::where('ref', $ref)->delete();
        ledger::where('ref', $ref)->delete();
        session()->forget('confirmed_password');
        return redirect('/withdraw')->with('error', "Withdraw Deleted");
    }

    public function vendors(){
        $accounts = account::where('type', 'Vendor')->get();
        $to_accounts = account::where('type', 'Business')->get();

        return view('vendor.list')->with(compact('accounts', 'to_accounts'));
    }

    public function customers(){
        $accounts = account::where('type', 'Customer')->get();
        $to_accounts = account::where('type', 'Business')->get();
        return view('customer.list')->with(compact('accounts', 'to_accounts'));
    }

    public function expense($from = 0, $to = 0){
        if($from == 0 || $to == 0)
        {
            $from = date('Y-m-d 07:00:s');
            $to = date('Y-m-d H:i:s');
        }
        $expenses = expense::orderBy('id', 'desc')->where('date', '>=', $from)->where('date', '<=', $to)->get();
        $accounts = account::where('type', 'Business')->get();
        return view('finance.expenses')->with(compact('expenses', 'accounts', 'from', 'to'));
    }

    public function storeExpense(request $req){
        $ref = getRef();
        $desc = "<strong>Expense</strong><br>" . $req->desc;
        expense::create(
            [
                'account_id' => $req->account,
                'date' => $req->date,
                'amount' => $req->amount,
                'desc' => $req->desc,
                'ref' => $ref,
            ]
        );
        createTransaction($req->account, $req->date, 0, $req->amount, $desc, "Expense", $ref);

        $p_acct = account::find($req->account);
        $ledger_head = "Expense";
        $ledger_type = $p_acct->title;
        $ledger_details = $req->desc;
        $ledger_amount = $req->amount;

        addLedger($req->date, $ledger_head, $ledger_type, $ledger_details, $ledger_amount, $ref);

        return back()->with('success', 'Expense saved');
    }

    public function deleteExpense($ref)
    {
        expense::where('ref', $ref)->delete();
        transactions::where('ref', $ref)->delete();
        ledger::where('ref', $ref)->delete();

        session()->forget('confirmed_password');
        return redirect('/expense')->with('error', "Expense Deleted");
    }

    public function transfer(){
        $accounts = account::all();

        $transfers = transfer::with('from_account', 'to_account')->orderBy('id', 'desc')->get();
        return view('finance.transfer')->with(compact('accounts', 'transfers'));

    }

    public function storeTransfer(request $req){
        if($req->from == $req->to)
        {
            return back()->with('error', "Please select different accounts to transfer");
        }

        $ref = getRef();

        transfer::create(
            [
                'from' => $req->from,
                'to' => $req->to,
                'account_id' => $req->account,
                'date' => $req->date,
                'amount' => $req->amount,
                'desc' => $req->desc,
                'ref' => $ref,
            ]
        );

        $from = account::find($req->from);
        $to = account::find($req->to);

        $desc = "<strong>Transfer to ".$to->title."</strong><br>" . $req->desc;
        $desc1 = "<strong>Transfer from ".$from->title."</strong><br>" . $req->desc;

        if($from->type == 'Business' && $to->type == 'Business'
            || $from->type == 'Customer' && $to->type == 'Customer'
            || $from->type == 'Business' && $to->type == 'Customer'
            || $from->type == 'Customer' && $to->type == 'Business'){
            createTransaction($req->from, $req->date, 0, $req->amount, $desc, "Transfer", $ref);
            createTransaction($req->to, $req->date, $req->amount, 0, $desc1, "Transfer", $ref);
        }
        if($from->type == 'Vendor' && $to->type == 'Vendor')
           {
            createTransaction($req->from, $req->date, $req->amount, 0, $desc, "Transfer", $ref);
            createTransaction($req->to, $req->date, 0, $req->amount, $desc1, "Transfer", $ref);
        }

        if($from->type == 'Vendor' && $to->type == 'Business'
            || $from->type == 'Vendor' && $to->type == 'Customer'){
            createTransaction($req->from, $req->date, $req->amount, 0, $desc, "Transfer", $ref);
            createTransaction($req->to, $req->date, $req->amount, 0, $desc1, "Transfer", $ref);
        }
        if($from->type == 'Business' && $to->type == 'Vendor'
            || $from->type == 'Customer' && $to->type == 'Vendor'){
            createTransaction($req->from, $req->date, 0, $req->amount, $desc, "Transfer", $ref);
            createTransaction($req->to, $req->date, 0, $req->amount, $desc1, "Transfer", $ref);
        }


        if($from->type == 'Customer' && $to->type == 'Business'){
            addLedger($req->date, $from->title, $to->title, "Received from Customer", $req->amount, $ref);
        }
        if($from->type == 'Business' && $to->type == 'Customer'){
            addLedger($req->date, $to->title, $from->title, "Payment to Customer", $req->amount, $ref);
        }
        if($from->type == 'Vendor' && $to->type == 'Business'){
            addLedger($req->date, $from->title, $to->title, "Received from Vendor", $req->amount, $ref);
        }
        if($from->type == 'Business' && $to->type == 'Vendor'){
            addLedger($req->date, $to->title, $from->title, "Payment to Vendor", $req->amount, $ref);
        }
        return redirect('/transfer/print/'.$ref);
    }

    public function deleteTransfer($ref)
    {
        transfer::where('ref', $ref)->delete();
        transactions::where('ref', $ref)->delete();

        session()->forget('confirmed_password');
        return redirect('/transfer')->with('error', "Transfer Deleted");
    }

    public function printTransfer($ref){
        $transfer = transfer::with('from_account', 'to_account')->where('ref', $ref)->first();
        $pre_balance_cr = 0;
        $pre_balance_db = 0;

        $cur_balance_cr = 0;
        $cur_balance_db = 0;
        if($transfer->from_account->type == "Business"){
            $pre_balance_cr = transactions::where('account_id', $transfer->to_account->id)->where('ref', '<', $ref)->sum('cr');
            $pre_balance_db = transactions::where('account_id', $transfer->to_account->id)->where('ref', '<', $ref)->sum('db');

            $cur_balance_cr = transactions::where('account_id', $transfer->to_account->id)->sum('cr');
            $cur_balance_db = transactions::where('account_id', $transfer->to_account->id)->sum('db');
        }
        elseif($transfer->to_account->type == "Business"){
            $pre_balance_cr = transactions::where('account_id', $transfer->from_account->id)->where('ref', '<', $ref)->sum('cr');
            $pre_balance_db = transactions::where('account_id', $transfer->from_account->id)->where('ref', '<', $ref)->sum('db');

            $cur_balance_cr = transactions::where('account_id', $transfer->from_account->id)->sum('cr');
            $cur_balance_db = transactions::where('account_id', $transfer->from_account->id)->sum('db');
        }

        $prev_balance = $pre_balance_cr - $pre_balance_db;
        $cur_balance = $cur_balance_cr - $cur_balance_db;

        return view('finance.payment_receipt_print')->with(compact('transfer', 'prev_balance', 'cur_balance'));
    }

    public function customersPurchase($id){
        $invoices = sale::with('details')->where('customer',$id)->orderBy('id', 'desc')->get();
        return view('customer.purchaseDetails')->with(compact('invoices'));
    }
    public function customersPurchasePDF($id){
        $invoices = sale::with('details')->where('customer',$id)->get();
        $pdf = PDF::loadView('customer.purchaseDetailsPDF', compact('invoices'));
        $file_name = $invoices[0]->customer_account->title." - Purchase Details.pdf";
        return $pdf->download($file_name);

    }
    public function vendorImport(request $req)
    {
        $file = $req->file;
        $extension = $file->getClientOriginalExtension();
        if($extension == "xlsx")
        {
            Excel::import(new vendorsImport, $file);
            return back()->with("success", "Successfully imported");
        }
        else
        {
            return back()->with("error", "Invalid file extension");
        }
    }

    public function customerImport(request $req)
    {
        $file = $req->file;
        $extension = $file->getClientOriginalExtension();
        if($extension == "xlsx")
        {
            Excel::import(new customersImport, $file);
            return back()->with("success", "Successfully imported");
        }
        else
        {
            return back()->with("error", "Invalid file extension");
        }
    }
}
