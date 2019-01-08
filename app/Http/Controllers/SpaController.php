<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SpaController extends Controller
{
    public function index()
    {
        $jsonArray = $this->getArrayGameJson();

        return view('spa', [
            "jsonArray" => $jsonArray,
        ]);
    }

    public function getArrayGameJson()
    {
        $urlFile = './../game.json';
        $file = file_get_contents($urlFile);
        $jsonArray = json_decode($file);
        return $jsonArray;
    }
}
