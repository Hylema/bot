<?php
/**
 * Created by PhpStorm.
 * User: newuser
 * Date: 17.12.2018
 * Time: 18:42
 */

namespace App\Bot\Conversations;

use App\Bot\Conversation;

/**
 * Class FirstOrder
 *
 * Первый заказ пользователя
 *
 * @package App\Bot\Conversations
 */

class ReOrder extends OsagoBase {
    public function processMessage($message)
    {
        $this->sendText('Продление полиса');
    }
}