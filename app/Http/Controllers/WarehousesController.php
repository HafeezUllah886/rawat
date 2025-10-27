<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\warehouses;
use Illuminate\Http\Request;

class WarehousesController extends Controller
{
    public function index()
    {
        $warehouses = warehouses::all();
        return view('warehouses.index', compact('warehouses'));
    }

    public function store(request $req)
    {
        warehouses::create(
            [
                'name' => $req->name,
            ]
        );

        return back()->with('success', 'Warehouses created successfully');
    }

    public function update(request $req)
    {
        warehouses::find($req->id)->update([
            'name' => $req->name,
        ]);

        return back()->with('success', 'Warehouses updated');
    }
}
