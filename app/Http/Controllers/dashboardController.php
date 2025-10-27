<?php

namespace App\Http\Controllers;

use App\Models\account;
use App\Models\expense;
use App\Models\ledger;
use App\Models\purchase;
use App\Models\sale;
use App\Models\transactions;

use Illuminate\Http\Request;
use App;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Session;

class dashboardController extends Controller
{
    public function dashboard(){

        $get_lang = auth()->user()->lang;
            App::setLocale($get_lang);
            Session::put('locale',$get_lang);
        /* Ledger Entries */
        $ledger = ledger::orderBy('id', 'desc')->get();

        /* Income and Expense */
        $all = account::with('transactions')->where('type', 'Business')->get();
        $accounts1 = [];
        foreach($all as $act)
        {
          $accounts1[] = $act->id;
        }
        $trans1 = transactions::whereIn('account_id', $accounts1)->get();

         /* Income and Expense */
         $cash = account::with('transactions')->where('Category', 'Cash')->get();
         $account = [];
         foreach($all as $act)
         {
           $account[] = $act->id;
         }
         $cashs = transactions::whereIn('account_id', $account)->get();
        return view('dashboard')->with(compact('ledger', 'trans1', 'cashs'));
    }

    public function settings(){

        return view('settings.settings');
    }

    public function customer_d(){
        $transactions = transactions::whereHas('account', function ($query) {
            $query->where('type', 'Customer');
        })->get();
        return view('dash_extra.customer_d')->with(compact('transactions'));
    }

    public function vendors_d(){
        $transactions = transactions::whereHas('account', function ($query) {
            $query->where('type', 'Vendor');
        })->get();
        return view('dash_extra.vendors_d')->with(compact('transactions'));
    }

    public function today_sale(){
        $history = sale::with('customer_account', 'account')->whereDate('date', today())->orderBy('id', 'desc')->get();
        return view('dash_extra.today_sale')->with(compact('history'));
    }

        public function today_purchase(){
            $history = purchase::with('vendor_account', 'account')->whereDate('date', today())->orderBy('id', 'desc')->get();
            return view('dash_extra.today_purchase')->with(compact('history'));
        }
    public function today_expense(){
        $expenses = expense::whereDate('date', today())->orderBy('id', 'desc')->get();
        $accounts = account::where('type', 'Business')->get();
        return view('dash_extra.today_expense')->with(compact('expenses', 'accounts'));
    }

    public function total_cash(){
        $transactions = transactions::whereHas('account', function ($query) {
            $query->where('Category', 'Cash');
        })->get();
        return view('dash_extra.total_cash')->with(compact('transactions'));
    }
    public function today_cash(){
        $date = date('Y-m-d');
        $transactions = transactions::whereDate('date', $date)->whereHas('account', function ($query) {
            $query->where('Category', 'Cash');
        })->get();
        return view('dash_extra.today_cash')->with(compact('transactions'));
    }

    public function total_bank(){
        $transactions = transactions::whereHas('account', function ($query) {
            $query->where('Category', 'Bank');
        })->get();
        return view('dash_extra.total_bank')->with(compact('transactions'));
    }
    public function today_bank(){
        $transactions = transactions::whereDate('date', today())->whereHas('account', function ($query) {
            $query->where('Category', 'Bank');
        })->get();
        return view('dash_extra.today_bank')->with(compact('transactions'));
    }

    public function ledgerDetails(){
        $ledger = ledger::orderBy('id', 'desc')->get();
        return view('dash_extra.ledgerDetails')->with(compact('ledger'));
    }

    public function incomeExpDetails(){
        $all = account::with('transactions')->where('type', 'Business')->get();
        $accounts1 = [];
        foreach($all as $act)
        {
          $accounts1[] = $act->id;
        }
        $trans1 = transactions::whereIn('account_id', $accounts1)->get();
        return view('dash_extra.income_exp_details', compact('trans1'));
    }


    public function changeLanguage(request $req){
        App::setLocale($req->lang);
        Session::put('locale',$req->lang);
        $user = User::where('id',auth()->user()->id)->first();
        $user->lang = $req->lang;
        $user->save();

        return redirect()->back()->with('msg', 'Language Changed');
    }
    public function profileUpdate(request $req){
        $req->validate(
            [
                'userName' => 'required',
                'email' => 'required|email',
            ]
        );

        $user = User::find(auth()->user()->id);
        $user->name = $req->userName;
        $user->email = $req->email;
        $user->save();

        return redirect('/logout');
    }

    public function passwordUpdate(request $req)
    {
        $req->validate(
            [
                'cPassword' => 'required',
                'nPassword' => 'required|min:6',
                'rPassword' => 'required|same:nPassword',
            ]
        );
        $user = User::find(auth()->user()->id);
        if(Hash::check($req->cPassword, $user->password))
        {
            $user->password = Hash::make($req->nPassword);
            $user->save();
        }
        else
        {
            return back()->with('error', 'Current Password is Wrong');
        }

        return back()->with('msg', "Password Changed");
    }

    function cashBook($date){
        $in = transactions::whereHas('account', function ($query) {
            $query->where('Category', 'Cash');
        })->where('cr', '>', 0)->whereDate('date', $date)->get();

        $out = transactions::whereHas('account', function ($query) {
            $query->where('Category', 'Cash');
        })->where('db', '>', 0)->whereDate('date', $date)->get();
        return view('dash_extra.cash_book', compact('in', 'out', 'date'));
    }
}
