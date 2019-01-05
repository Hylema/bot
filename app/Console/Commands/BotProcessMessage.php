<?php

namespace App\Console\Commands;

use App\Bot\Osago;
use App\Models\Message;
use App\User;
use Illuminate\Console\Command;

class BotProcessMessage extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */

    protected $signature = 'osago:process';

    /**
     * The console command description.
     *
     * @var string
     */

    protected $description = 'Обработка полученных сообщений бота';

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
        $this->info("Processing messages");

        $userIds = Message::select('user_id')->distinct()->get();
        $users = User::find($userIds);
        foreach ($users as $user) {
            $messagesCollections[] = $user->messages()
                ->where('status', 'queue')
                ->where('direction', 'in')
                ->limit(10)
                ->get();
        }

        if (count($users) == 0) {
            $this->error("No messages to process");
        }


        foreach ($messagesCollections as $messagesCollection) {
            foreach ($messagesCollection as $message) {
                $user = $message->sender()->first();
                $bot = new Osago($user);
                $bot->processMessages($messagesCollection);
                $message->status = 'processed';
                $message->save();
                $this->info("Message #" . $message->id . ' processed');
            }
        }
    }
}
