<?php

namespace App\Bot\Conversations;

use App\Bot\Conversation;
use App\Bot\Conversations\FirstOrder;

/**
 * Class Basic
 *
 * Разговор с пользователем по умолчанию, по сути главное меню бота
 *
 * @package App\Bot\Conversations
 */

class Basic extends Conversation
{
    public function processMessage($message)
    {
        if (!$this->userIsWelcomed) {
            $this->sendText('Привет! Добро пожаловать в ОсагоБот');
            $this->sendText('Доступные опции:' . PHP_EOL . '1. Новый полис' . PHP_EOL . '2. Продлить существующий полис'. PHP_EOL .'3. Игра');
            $this->userIsWelcomed = true;
            return;
        }

        if ($message->text !== '') {
            switch ($message->text) {
                case '1':
                    $this->bot->selectConversation('firstOrder');
                    if (!$this->firstOrder) {
                        $this->sendText('Напишите мне свое фИО');
                        $this->firstOrder = 'getFullName';
                    }
                    break;
                case '2':
                    $this->bot->selectConversation('ReOrder');
                    break;
                case '3':
                    $this->sendText('Введите: 1 - начать игру, или 2 - вступить в игру');
                    $this->bot->selectConversation('AdminOrPlayer');
                    break;
                default:
                    $this->sendText('Я пока не умею обрабатывать такие команды :(');
                    break;
            }
        }
    }
}
