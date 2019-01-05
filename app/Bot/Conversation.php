<?php

namespace App\Bot;

use App\Models\Message;

class Conversation {
    protected $bot;

    function __construct($bot)
    {
        $this->bot = $bot;
    }

    public function processMessage($message) {

    }

    /**
     *
     * Получает параметр из State пользователя
     *
     * @param $name
     */
    public function __get($name) {
        return $this->bot->getStateVar($name);
    }

    /**
     *
     * Устанавливает параметр в State пользователя. Для частных переменных разговора лучше использовать префикс с названием класса разговора, например conversation_variable
     *
     * @param $name
     * @param $value
     */
    public function __set($name, $value) {
        return $this->bot->setStateVar($name, $value);
    }

    /**
     * Отправляет пользователю текстовое сообщение
     *
     * @param $text
     */
    public function sendText($text) {
        return Message::create([
            'user_id' => $this->bot->getUser()->id,
            'text' => $text,
            'direction' => 'out',
            'status' => 'queue'
        ]);
    }
}