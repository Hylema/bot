<?php
/**
 * Created by PhpStorm.
 * User: newuser
 * Date: 24.12.2018
 * Time: 14:01
 */
namespace App\Console\Commands;

use App\Bot\Quiz;
use App\Transformators\TelegramMessageTransformator;
use Illuminate\Console\Command;


class ReceptionMessage extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'osago:reception';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Получение сообщения из Telegram';

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
    public function getId()
    {
        return $this->updateId;
    }

    public function handle()
    {

        $tempPath = storage_path('app/telegramFetchlastUpdateid');
        $this->info("Fetch Telegram messages");
        $bot = new \TelegramBot\Api\BotApi(env('TELEGRAM_BOT_TOKEN'));
        $transformator = new TelegramMessageTransformator();

        if (is_file($tempPath)) {
            $offset = (int)file_get_contents($tempPath);
        } else {
            $offset = 0;
        }

        while(true) {
            $result = $bot->getUpdates($offset);
            if(count($result) === 0) {
                $this->info("No messages to fetch");
            } else {
                foreach ($result as $message){
                    $transformed = $transformator->transform($message);
                    $offset = $message->getUpdateId() + 1;
                    dump($transformed);
                    if($transformed){
                        $send = new Quiz($transformed);
                        $send->processMessage($transformed);
//                        $bot->sendMessage($transformed['messenger_user_id'] ,$transformed['text']);
                    }
                }
            }

        }
    }
}