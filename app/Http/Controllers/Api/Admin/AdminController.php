<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::check()) {
            return 3;
        }
        return Auth::check() . 4;
        //return Auth::user();
        //dd(Auth::check());
    }
}
