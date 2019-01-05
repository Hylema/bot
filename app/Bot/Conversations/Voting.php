<?php

namespace App\Bot\Conversations;

class Voting extends Game
{
    public function processMessage($message)
    {
        //Номер определения которое выбрал игрок
        switch ($message->text)
        {
            case '1':
                $this->resultVoting(0);
                break;
            case '2':
                $this->resultVoting(1);
                break;
            case '3':
                $this->resultVoting(2);
                break;
            case '4':
                $this->resultVoting(3);
                break;
            default:
                $this->sendText('Пожалуйста, выберите один из перечисленных вариантов: ');
                $this->sendDefinition();
        }
    }

    public function resultVoting($answerPlayer)
    {
        $this->whoNeedsPoints($answerPlayer);

        $this->cycleNSec('checkVotingPlayers', 3);
        $this->sendText('Результаты игры: ');
        $this->whoPlayerChoose($answerPlayer);
        $this->gameResult();

        //Удалить слово у админа
        $this->deleteWord();
        //Обнулить определения
        $this->toZeroDefinition();
    }

    public function whoPlayerChoose($answerPlayer)
    {
        $openFile = $this->open();

        if($answerPlayer == $this->getKeyAdmin()) {
            return $this->sendText('Вы выбрали ответ игрока '.$openFile[$answerPlayer]->nickName.', этот игрок является админом, поэтому вы получаете 3 очка!');
        } else {
            return $this->sendText('Вы выбрали ответ игрока '.$openFile[$answerPlayer]->nickName.', этот игрок является обычным игроком, поэтому он получает 1 очко');
        }
    }

    //метод для удаления все определений
    public function toZeroDefinition()
    {
        $fileOpen = $this->open();
        foreach ($fileOpen as $keys => $values){
            $fileOpen[$keys]->definition = '';
        }
        $this->save($fileOpen);
    }

    public function whoNeedsPoints($answerPlayer)
    {
        $openFile = $this->open();

        $keyAdmin = $this->getKeyAdmin();

        if($keyAdmin == $answerPlayer){
            $key = $this->getKeyPlayer();
            $num = 3;
        } else {
            $key = $answerPlayer;
            $num = 1;
        }

        $openFile[$key]->points += $num;
        $openFile[$keyAdmin]->playersReady += 1;

        $this->save($openFile);

    }
}