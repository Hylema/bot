<?php

namespace App\Console\Commands;

use App\Http\Controllers\MessagesController;
use App\Models\Message;
use App\Transformators\ConsoleMessageTransformator;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use App\Bot\Osago;

class FetchConsoleUpdates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'osago:fetchConsole {user}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Получает новые сообщения из Console';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $user = $this->argument('user');
        //var_dump($user);
        $user = User::findUserByMessengerId($user, 'console');
        $bot = new Osago($user);
        
        while (true) {
            $messageText = $this->ask('Input:');
            $messageRow = new Message([
                'user_id' => $user->id,
                'direction' => 'in',
                'status' => 'new',
                'text' => $messageText,
            ]);
            $messageRow->save();
            $user->last_messenger = 'console';
            $user->save();
            $bot->processMessage($messageRow);
            $this->call('osago:response');
        }
        
    }
}
