<?php

use Illuminate\Foundation\Inspiring;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('bottest', function () {

    /*$bot = new \TelegramBot\Api\BotApi(env('TELEGRAM_BOT_TOKEN'));

    $result = $bot->getUpdates();
    dd($result);*/
    //66301433

    ///$bot->sendMessage('66301433', "YO man!");
    $i = 0;
    while (true) {

        echo $i++;
        sleep(1);
    }

});
