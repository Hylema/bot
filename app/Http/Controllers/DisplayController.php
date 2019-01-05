<?php
/**
 * Created by PhpStorm.
 * User: newuser
 * Date: 19.12.2018
 * Time: 11:46
 */

namespace App\Http\Controllers;
use Illuminate\Routing\Controller as BaseController;



Class DisplayController extends BaseController{

    public function index() {

        $row = \App\Models\VehiclePassport::all();

        if (!$row){
            return 'Нет фото для обработки';

        } else{
            foreach ($row as $key => $file){
//                var_dump($file->photo_path);
                $img[] = $file->photo_path[$key];
                $img = str_replace("\"", "", $img);

            }
            return view('display.app', [
                'img' => $img
            ]);
        }

    }
}