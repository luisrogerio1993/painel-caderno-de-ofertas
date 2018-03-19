<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index(){
        $title = 'Painel - Caderno de Ofertas';
        
        return view('home.index', compact('title'));
    }
    public function teste(){
        return view('home.teste');
    }
}
