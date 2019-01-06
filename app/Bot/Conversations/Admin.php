<?php

namespace App\Bot\Conversations;

class Admin extends Game
{

    public $adminId;
    public $arrayDataForAdmin = [];
    public $gameArray = [];

    public function processMessage($message)
    {
        //Этапы для админа
        switch ($this->stageForAdmin)
        {
            case '1':
                $this->adminNickName($message->text);
                break;
            case '2':
                $this->adminWord($message->text);
                break;
            case '3':
                $this->adminDefinition($message->text);
                break;
            case '4':
                $this->checkPlayers();
                break;
            default:
                $this->sendText("Что-то пошло не так");
        }

    }

    //Первый этап у админа, админ должен написать свое имя и мы его присваиваем к фаулу json.
    public function adminNickName($nickName)
    {
        $this->nickName = $nickName;

        //Переводим админа на следующий этап
        $this->stageForAdmin = '2';

        $this->sendText('Загадайте слово');
    }

    //Второй этап у админа, админ должен написать свое загаданное слово и мы его присваиваем к фаулу json.
    public function adminWord($word)
    {
        $this->adminWord = $word;

        //Переводим админа на следующий этап
        $this->stageForAdmin = '3';

        $this->sendText('Загадайте определение');
    }

    //Третий этап у админа, админ должен написать свое определение к загаданному слуво и мы его присваиваем к фаулу json.
    public function adminDefinition($definition)
    {
        $this->definition = $definition;
        $this->adminStatus = false;
        $this->playersReady = 0;
        $this->points = 0;

        //Переводим админа на последний этап
        $this->stageForAdmin = '4';


        //Мы делаем проверку на пустоту файла
        if($this->ifFileNotEmpty()){
            //Для повтороной игры. Происходит перезапись данных в game.json
            $fileOpen = $this->open();
            $keyAdmin = $this->getKeyAdmin();
            $fileOpen[$keyAdmin]->adminWord = $this->adminWord;
            $fileOpen[$keyAdmin]->definition = $this->definition;
            $fileOpen[$keyAdmin]->adminStatus = $this->adminStatus;
            $fileOpen[$keyAdmin]->playersReady = $this->playersReady;
            $this->save($fileOpen);
        } else {
            //Мы создаем массив данных для игрового файла game.json
            $this->arrayDataForAdmin['nickName'] = $this->nickName;
            $this->arrayDataForAdmin['adminWord'] = $this->adminWord;
            $this->arrayDataForAdmin['definition'] = $this->definition;
            $this->arrayDataForAdmin['adminStatus'] = $this->adminStatus;
            $this->arrayDataForAdmin['playersReady'] = $this->playersReady;
            $this->arrayDataForAdmin['points'] = $this->points;

            //Для первой игры. Происходит запись данных в game.json
            $this->gameArray[] = $this->arrayDataForAdmin;
            $data = $this->gameArray;
            $this->save($data);
        }

        $this->checkPlayers();
    }

    public function checkPlayers()
    {
        //Когда игроков будет четверо и каждый из них напишет свое определение - цикл пропустит дальше
        $this->cycleNSec('checkReadyPlayers', 3);

        //Мешаем массив данных game.json, чтобы не было так очевидно чье где определение находится
        //А так же мы изменяем статус админа. Чтобы игроки поняли, что админ уже перемешал данные
        $this->shakeArrayAndChangeStatusForAdmin();

        //Когда каждый из игроков проголосует (выберут определение других игроков) - цикл пропустит дальше
        $this->cycleNSec('checkVotingPlayers', 5);

        //Мы отправляем админа к результатам игры, так как ему нет необходимости голосовать
        $this->gameResult();

    }

    //Метод для перемешивания массива данных файла game.json и устанавливает флаг для игроков
    public function shakeArrayAndChangeStatusForAdmin()
    {
        $fileOpen = $this->open();
        $fileOpen[$this->getKeyAdmin()]->adminStatus = true;
        shuffle($fileOpen);
        $this->save($fileOpen);
    }

}