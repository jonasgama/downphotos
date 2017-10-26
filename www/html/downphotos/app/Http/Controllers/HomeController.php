<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {


        $carousel = \App\Imagem::oldest()->take(3)->get();

        $miniGaleria = \App\Imagem::latest()->take(11)->get();
        //dd($carousel->get());
        //11 fotos para a galeria secundári

        return view('index', compact('carousel', 'miniGaleria'));
    }

    public function about(){

        return view('layouts.sobre');
    }
    public function time(){
        
        return view('layouts.time');
    }
    public function faq(){
        
        return view('layouts.faq');
    }


}
