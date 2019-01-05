<?php

namespace App\Console\Commands;

use App\Models\Message;
use App\Transformators\TelegramMessageTransformator;
use App\User;
use Illuminate\Console\Command;

class FetchTelegramUpdates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'osago:fetchTelegram';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Получает новые сообщения из Telegram';

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
     * @throws \TelegramBot\Api\Exception
     * @throws \TelegramBot\Api\HttpException
     * @throws \TelegramBot\Api\InvalidArgumentException
     */
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
            $this->line("Fetching from offset ". $offset);
            $result = $bot->getUpdates($offset);
            $userPhotosDirectory = '';
            $fileName = '';

            if(count($result) === 0) {
                $this->info("No messages to fetch");
            } else {
                $added = 0;
                foreach ($result as $message) {
                    $transformed = $transformator->transform($message);
                    $offset = $message->getUpdateId() + 1;

                    if (!empty($transformed['photo'])) {
                        $photo = array_pop($transformed['photo']);
                        $photoId = $photo->getFileId();
                        $binaryPhoto = $bot->downloadFile($photoId);
                        $fileName =  $transformed['messenger_user_id'] . '_' .time() . mt_rand(0,999) . '.jpeg';
                        $userPhotosDirectory = 'documents_photos/' . $transformed['messenger_user_id'] . '/';

                        if (!is_dir(storage_path($userPhotosDirectory))) {
                            mkdir(storage_path($userPhotosDirectory), 0777, true);
                        }

                        file_put_contents(storage_path($userPhotosDirectory . $fileName), $binaryPhoto);
                    }

                    $user = User::findUserByMessengerId($transformed['messenger_user_id'], 'telegram');

                    $messageRow = new Message([
                        'user_id' => $user->id,
                        'direction' => 'in',
                        'text' => $transformed['text'],
                        'body' => $userPhotosDirectory . $fileName,
                        'last_messenger' => 'telegram',
                    ]);
                    $messageRow->save();
                    $added++;
                }
                $this->info("Added " . $added . ' message(s)');
                file_put_contents($tempPath, $offset);
                $this->call('osago:process');
            }
            $this->call('osago:sendNotifications');
            $this->call('osago:response');
            sleep(3);
        }


    }
}
