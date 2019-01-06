<?php

namespace App\Bot\Conversations;

class AdminOrPlayer extends OsagoBase
{
    public function processMessage($message)
    {
        //Распределяет админа и игроков
        switch ($message->text){
            case '1':
                $this->sendText('Введите свой nickName');
                $this->user = 'Admin';
                $this->stageForAdmin = '1';
                $this->bot->selectConversation('Admin');
                break;
            case '2':
                $this->sendText('Введите свой nickName');
                $this->user = 'Player';
                $this->stageForPlayer = '1';
                $this->bot->selectConversation('Player');
                break;
        }
    }
}
