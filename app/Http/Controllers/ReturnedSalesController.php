<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\returned_sales;
use Illuminate\Http\Request;

class ReturnedSalesController extends Controller
{
    public function index()
    {
        return view('returns.index');
    }

    public function view(request $req)
    {
        $returns = returned_sales::whereBetween('date', [$req->from, $req->to])->get();

        return view('returns.list', compact('returns'));
    }
}
