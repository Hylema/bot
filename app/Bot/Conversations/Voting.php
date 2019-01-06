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
        //Начисления очков
        $this->whoNeedsPoints($answerPlayer);

        //Ждем пока все игроки получат очки
        $this->cycleNSec('checkVotingPlayers', 3);


        $this->sendText('Результаты игры: ');

        //Выводит кого игрок выбрал
        $this->whoPlayerChoose($answerPlayer);


        $this->gameResult();

        //Удалить слово у админа
        $this->deleteWord();
        //Обнулить определения
        $this->toZeroDefinition();
    }

    //Метод для вывода сообщения кого выбрал игрок
    public function whoPlayerChoose($answerPlayer)
    {
        $openFile = $this->open();

        if($answerPlayer == $this->getKeyAdmin()) {
            return $this->sendText('Вы выбрали ответ игрока '.$openFile[$answerPlayer]->nickName.', этот игрок является админом, поэтому вы получаете 3 очка!');
        } else {
            return $this->sendText('Вы выбрали ответ игрока '.$openFile[$answerPlayer]->nickName.', этот игрок является обычным игроком, поэтому он получает 1 очко');
        }
    }

    //Метод для определения кого выбрал игрок, если игрок выбрал админа - ему начисляется 3 очка, если игрок выбрал другого игрока, тогда этому игроку начисляется 1 очко
    //Так же игрок ставит флаг, что ему уже начислились очки (Нужно для того, чтобы когда игрок пройдет уже этот этап, он начал ждать других)
    public function whoNeedsPoints($answerPlayer)
    {
        $openFile = $this->open();

        $keyAdmin = $this->getKeyAdmin();

        //Если игрок выбрал админа
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