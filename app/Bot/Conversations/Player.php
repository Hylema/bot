<?php

namespace App\Bot\Conversations;

class Player extends Game{

    public $arrayDataForPlayer = [];
    public $objDataForPlayer = [];

    public function processMessage($message)
    {
        //Этапы для игрока
        switch ($this->stageForPlayer)
        {
            case '1':
                $this->playerNickName($message->text);
                break;
            case '2':
                $this->putDefinitionPlayerInFile($message->text);
                break;
            case '3':
                $this->checkPlayers();
                break;
            default:
                $this->sendText('Что-то пошло не так');
        }
    }

    //Первый этап у игрока, игрок должен написать свое имя и мы его присваиваем к фаулу json.
    public function playerNickName($nickName)
    {
        //Переводим игрока на следующий этап
        $this->stageForPlayer = '2';

        $this->playerNickName = $nickName;
        $this->playerPoints = 0;

        //Показать слово админа
        $this->hiddenWord();
    }

    public function putDefinitionPlayerInFile($definition)
    {
        //Переводим игрока на последний этап
        $this->stageForPlayer = '3';

        $this->playerDefinition = $definition;

        //Если у игрока нету id, этот метод создаст его ему
        $this->idGenForPlayer();


        if(count($this->open()) >= 4){
            //Для повторной игры
            $fileOpen = $this->open();
            $fileOpen[$this->getKeyPlayer()]->definition = $this->playerDefinition;
            $this->save($fileOpen);
        } else {
            //Для первой игры
            $this->arrayDataForPlayer['nickName'] = $this->playerNickName;
            $this->arrayDataForPlayer['points'] = $this->playerPoints;
            $this->arrayDataForPlayer['definition'] = $this->playerDefinition;

            //Для отображения на сайте
            $this->arrayDataForPlayer['playerChoose'] = "";
            $this->arrayDataForPlayer['adminOrPlayer'] = "";

            //В массив game.json ыы добавляем нового игрока
            $this->addPlayerForGame();
        }

        $this->checkPlayers();
    }

    public function checkPlayers()
    {
        //Когда игроков будет четверо и каждый из них напишет свое определение - цикл пропустит дальше
        $this->cycleNSec('checkReadyPlayers', 3);

        //Проверям, перемешал ли админ массив данных перед началом игрой
        $this->cycleNSec('checkAdminStatus', 3);

        //Начинается игра для игроков
        $this->startGame();
    }

    //Метод проверяет перемешал ли админ массив данных в файле game.json
    public function checkAdminStatus()
    {
        $fileOpen = $this->open();
        dump($this->getKeyAdmin());
        if($fileOpen[$this->getKeyAdmin()]->adminStatus == true){
            return true;
        }
        return false;
    }

    //Если у игрока нету id, этот метод создаст его ему
    public function idGenForPlayer()
    {
        $checkId = $this->id;
        if(empty($checkId)) {
            $num = 1;
            $openFile = $this->open();
            foreach ($openFile as $keys => $values) {
                if (isset($values->id)) {
                    $num++;
                }
            }
            $this->id = $num;
            $this->arrayDataForPlayer['id'] = $num;
        }
    }

    //В массив game.json ыы добавляем нового игрока
    public function addPlayerForGame()
    {
        $file = $this->open();
        $this->objDataForPlayer[] = $this->arrayDataForPlayer;
        $json = json_encode(array_merge($file, $this->objDataForPlayer));
        file_put_contents('game.json', $json);
    }
}
