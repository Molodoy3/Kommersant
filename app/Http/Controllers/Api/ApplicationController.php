<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class ApplicationController extends Controller
{
    public function store(Request $request)
    {
        return response()->json($request->all());
    }
}
