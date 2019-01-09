<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SpaController extends Controller
{
    public function index()
    {
        return view('spa');
    }

    public function getArrayGameJson()
    {
        $urlFile = './../game.json';
        $file = file_get_contents($urlFile);
        $jsonArray = json_decode($file);
        return $jsonArray;
    }
}
