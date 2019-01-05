<?php

namespace App\Bot\Conversations;

class GameResult extends Game
{
    public function processMessage($message)
    {
        switch ($message->text){
            case '?':
                $this->searchId($this->id);
                break;
            case '+':
                $this->newGame();
                break;
            case 'exit':
                $this->bot->selectConversation('Basic');
                break;
            default:
                $this->sendText('Для начала новой игры введите: +');
        }
    }

    public function newGame()
    {
        if($this->user == 'Admin'){
            $this->stageForAdmin = '2';
            $this->bot->selectConversation('Admin');
            $this->sendText('Загадайте слово');
        } else {
            $this->stageForPlayer = '2';
            $this->bot->selectConversation('Player');
            $this->hiddenWord();
        }
    }

    public function searchId($id)
    {
        $openFile = $this->open();
        foreach($openFile as $keys => $values){
            if($values->id == $id){
                return dump($values);
            }
        }
    }
}