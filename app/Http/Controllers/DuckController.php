<?php

namespace App\Http\Controllers;

use App\Duck;
use Illuminate\Http\Request;

class DuckController extends Controller
{
    public function search()
    {
        if($query = \Request::get('query'))
        {
            $results=Duck::search('query')->get();

            return view('duck_search',['query'=>$query,'results'=>$results]);
        }
        else
        {
            return view('duck_search',['query'=>null]);

        }
    }

    public function advanced()
    {
        if($query = \Request::get('query'))
        {
            $duck_name = \Request::get('duck_name');
            $duck_age= \Request::get('duck_age');
            $duck_color=\Request::get('duck_color');

            $results=Duck::search('query')->paginate(3);

            if($duck_name){
                $results = $results->where('name',$duck_name);
            }
            if($duck_age){
                $results = $results->where('name',$duck_age);
            }
            if($duck_color){
                $results = $results->where('name',$duck_color);
            }

            $results = $results->paginate(3);
            return view('duck_advanced_search',['query'=>$query,'results'=>$results,'page'=>\Request::get('page') ?: 1]);
        }
        else
        {
            return view('duck_advanced_search',['query'=>null]);

        }
    }
}
