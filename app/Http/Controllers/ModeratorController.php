<?php

namespace App\Http\Controllers;

use App\Models\DriverLicense;
use App\User;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;

class ModeratorController extends BaseController
{
    use DispatchesJobs, ValidatesRequests;

    public function index() {
        $license = DriverLicense::where('photo_path', '!=', '')
            ->where('status', 'processing')
            ->first();
        if (!$license) {
            return 'Нет ничего для обработки';
        }
        return view('moderator.list', [
            'license' => $license
        ]);
    }

    public function process(Request $request, $id) {
        $license = DriverLicense::find($id);
        if (!$license) {
            return 'Не найдено';
        }
        $license->series = $request->get('series');
        $license->number = $request->get('number');
        $license->issuance_date = $request->get('issuance_date');
        $license->status = 'processed';
        $license->save();

        $user = User::find($license->user_id);
        $user->notified = false;
        $user->save();

        return redirect('/licenses/');
    }
}
