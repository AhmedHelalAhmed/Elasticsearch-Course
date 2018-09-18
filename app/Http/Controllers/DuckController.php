<?php

namespace App\Http\Controllers;

use App\Duck;
use Illuminate\Http\Request;

class DuckController extends Controller
{
    public function search()
    {
        return view('duck_search');
    }
}
