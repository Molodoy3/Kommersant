<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index() {
        return response()
            ->json(
                Service::all()
            //Category::with('services')->get()
            );
    }

}
