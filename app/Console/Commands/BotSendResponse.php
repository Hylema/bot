<?php

namespace App\Console\Commands;

use App\Models\Message;
use App\Models\UserMessengers;
use Illuminate\Console\Command;

class BotSendResponse extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'osago:response';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Отправка ответов пользователям';

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
        $bot = new \TelegramBot\Api\BotApi(env('TELEGRAM_BOT_TOKEN'));
        $this->info('Sending responses');
        $messages = Message::where('direction', 'out')
            ->where('status', 'queue')
            ->limit(10)
            ->get();

        if (count($messages) === 0) {
            $this->error('No responses to send');
        }

        foreach ($messages as $message) {
            $user = $message->sender()->first();
            $userId = $user->id;

            $userMessengerId = UserMessengers::find($userId)->messenger_user_id;
            if ($user->last_messenger == 'console') {
                $this->info($message->text);
            } else {
                // Telegram
                $bot->sendMessage($userMessengerId, $message->text);   
            }

            $message->status = 'sent';
            $message->save();
        }
    }
}
