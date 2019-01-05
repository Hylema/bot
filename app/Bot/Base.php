<?php
namespace App\Bot;

class Base {
    protected $conversations = [
    ];

    protected $state = [];

    protected $user = null;
    protected $id = null;
    protected $conversation = null;
    protected $messagesToProcess = [];
    /**
     * Загружает State текущего пользователя
     */

    public function loadState() {
        $this->state = @json_decode(file_get_contents(storage_path('/state/' . $this->id . '.json')), true);
    }

    /**
     * Сохраняет State текущего пользователя
     */

    public function saveState() {
        @mkdir(storage_path('state/'));
        file_put_contents(storage_path('state/' . $this->id . '.json'), json_encode($this->state));
    }

    /**
     * Устанавливает переменную в State
     */
    public function setStateVar ($name, $value) {
        $this->state[$name] = $value;
        $this->saveState();
    }

    public function getUser() {
        return $this->user;
    }

    /**
     *
     * Получает переменную из state
     *
     * @param $name
     * @return mixed
     */

    public function getStateVar($name) {
        return @$this->state[$name];
    }

    public function processMessage($message){
        $this->currentConversation()->processMessage($message);
    }

    public function processMessages($messages){
        $this->messagesToProcess = $messages;
        while ($message = $this->messagesToProcess->shift()) {
            $this->processMessage($message);
        }
    }

    public function countMessagesToProcess() {
        return $this->messagesToProcess->count();
    }

    public function getNextMessageToProcess() {
        return $this->messagesToProcess->get(0);
    }

    public function removeMessageFromProcess($messageToDelete) {
        $this->messagesToProcess = $this->messagesToProcess->filter(function ($message) use ($messageToDelete)
        {
            return ($message != $messageToDelete);
        });
    }

    public function currentConversation() {
        if ($this->conversation != null) {
            return $this->conversation;
        }
        if (isset($this->state['conversation'])) {
            $class = $this->conversations[$this->state['conversation']];
        } else {
            $class = $this->conversations['default'];
        }
        return $this->conversation = new $class($this);
    }

    public function selectConversation($name) {
        if (isset($this->conversations[$name]) && $this->getStateVar('conversation') != $name) {
            $this->setStateVar('conversation', $name);
            $class = $this->conversations[$name];
            $this->conversation = new $class($this);
        }
        return $this->currentConversation();
    }
}
