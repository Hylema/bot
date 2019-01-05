<?php

namespace App\Bot\Conversations;

class Game extends OsagoBase
{
    //Метод открывает файл game.json и возвращает массив данных
    public function open()
    {
        $file = file_get_contents('game.json');
        $json = json_decode($file);
        return $json;
    }

    //Метод сохраняет данные в game.json
    public function save($file)
    {
        $json = json_encode($file);
        file_put_contents('game.json', $json);
    }

    //Метод для циклов, первым параметром мы принимаем имя функции, а вторым - через сколько сек её проверять
    public function cycleNSec($nameFunc, $sec)
    {
        while ($this->$nameFunc() == false){
            $this->$nameFunc();
            sleep($sec);
        }
    }





    public function startGame()
    {
        //Перенапровлеям игрока на конверс "голосование"
        $this->bot->selectConversation('Voting');

        $this->sendText('Определение игроков:');

        //Выводим определения всех игроков игроку
        $this->sendDefinition();

        $this->sendText('Выберите самое подходящее определение');
    }

    //Метод выводит определение всех игроков ирокам
    public function sendDefinition()
    {
        $arrAnswer = $this->open();
        for($i = 0; $i < 4; $i++) {
            $this->sendText(1 + $i.' - '.$arrAnswer[$i]->definition);
        }
    }





    public function gameResult()
    {
        $this->sendResult();

        if($this->ifPlayerHave10Points()){
            //игра окончена
            $this->gameEnd();
        } else {
            //игра продолжается
            $this->gameContinue();
        }
    }

    public function sendResult()
    {
        $arrAnswer = $this->open();
        for($i = 0; $i < 4; $i++) {
            $this->sendText('У игрока '.$arrAnswer[$i]->nickName.' - '.$arrAnswer[$i]->points.' очков');
        }
    }

    public function ifPlayerHave10Points()
    {
        $fileOpen = $this->open();
        foreach ($fileOpen as $values){
            if($values->points >= 10){
                return true;
            }
        }
        return false;
    }

    public function gameContinue()
    {
        $this->sendText('Чтобы продолжить игру - напишите +');
        $this->bot->selectConversation('GameResult');
    }

    public function gameEnd()
    {
        $this->sendText('Игра окончена, победил игрок с ником - '.$this->getNameWinner());
        $this->bot->selectConversation('Basic');
    }





    //Метод вернет true когда игроков будет четверо и каждый из них напишет свое определение
    public function checkReadyPlayers(){
        $fileOpen = $this->open();
        $num = 0;
        if(count($fileOpen) >= 4){
            foreach ($fileOpen as $keys => $values){
                if(!empty($values->definition)){
                    $num += 1;
                }
            }

            if($num == 4){
                return true;
            } else {
                return false;
            }
        }
        return false;
    }

    public function getNameWinner()
    {
        $fileOpen = $this->open();
        return $fileOpen[$this->getIndexWinner()]->nickName;
    }

    public function getIndexWinner()
    {
        $fileOpen = $this->open();
        foreach ($fileOpen as $keys => $values){
            if($values->points >= 10){
                return $keys;
            }
        }
        return false;
    }

    public function checkVotingPlayers()
    {
        $fileOpen = $this->open();
        if($fileOpen[$this->getKeyAdmin()]->playersReady >= 3){
            return true;
        }
        return false;
    }

    //Метод для того, чтобы игроку показать загаданное слово админа
    public function hiddenWord()
    {
        //Если файл не пустой, то цикл пропускает нас дальше
        $this->cycleNSec('ifFileNotEmpty', 3);

        //Если админ загадал уже слово, то цикл пропускает нас дальше
        $this->cycleNSec('checkAdminWord', 3);

        $this->sendText('Загаданное слово админа: '.$this->getAdminWord().', напишите свое определение.');
    }

    public function checkAdminWord()
    {
        if($this->getAdminWord() != ''){
            return true;
        } else {
            return false;
        }
    }

    //Получить загаданное слово админа
    public function getAdminWord()
    {
        $openFile = $this->open();
        return $openFile[$this->getKeyAdmin()]->adminWord;
    }

    public function deleteWord()
    {
        $openFile = $this->open();
        $openFile[$this->getKeyAdmin()]->adminWord = '';
        $this->save($openFile);
    }

    //Получаем ключ админа
    public function getKeyAdmin()
    {
        $openFile = $this->open();
        foreach($openFile as $keys => $values){
            foreach($values as $key => $value){
                if($key == 'playersReady'){
                    return $keys;
                }
            }
        }
    }

    //Получаем ключ игрока
    public function getKeyPlayer()
    {
        $id = $this->id;
        $openFile = $this->open();

        foreach($openFile as $keys => $values){
            if(isset($values->id)){
                if($values->id == $id){
                    return $keys;
                }
            }
        }

//        foreach ($openFile as $keys => $values){
//            foreach ($values as $key => $value){
//                if($value == $id){
//                    return
//                }
//            }
//        }
    }

    //Метод для проверки пустоты файла
    public function ifFileNotEmpty()
    {
        $fileOpen = $this->open();
        if(!empty($fileOpen)){
            return true;
        } else {
            return false;
        }
    }
}