<?php

namespace App\Transformators;

class TelegramMessageTransformator {
    public function transform($message) {
        dump($message);
        $data = [];

        if ($message->getMessage() == null){
            $callback = $message->getCallbackQuery()->getData();
            $id = $message->getCallbackQuery()->getFrom()->getId();
            $firstName = $message->getCallbackQuery()->getMessage()->getFrom()->getFirstName();
            $lastName = $message->getCallbackQuery()->getMessage()->getFrom()->getLastName();
            $text = $message->getCallbackQuery()->getMessage()->getText();
            $phone = $message->getCallbackQuery()->getMessage()->getContact();
            $data['callback'] = $callback;
        } else{
            $id = $message->getMessage()->getFrom()->getId();
            $firstName = $message->getMessage()->getFrom()->getFirstName();
            $lastName = $message->getMessage()->getFrom()->getLastName();
//        $languageCode = $message[0]->getMessage()->getFrom()->getLanguageCode();
            $text = $message->getMessage()->getText();
            $photo  = $message->getMessage()->getPhoto();
            $phone = $message->getMessage()->getContact();

            $data['photo'] = $photo;
        }




        $data['messenger_user_id'] = $id;
        $data['firstName'] = $firstName;
        $data['lastName'] = $lastName;
//        $data['languageCode'] = $languageCode;
        $data['text'] = $this->remove_emoji($text);

        $data['phone'] = $phone;


        return $data;
    }


    public function strReplace($str) {
        $result = str_replace(' ', '', $str);
        return dd($result);
    }

    function remove_emoji($text){
        return preg_replace('/([0-9|#][\x{20E3}])|[\x{00ae}|\x{00a9}|\x{203C}|\x{2047}|\x{2048}|\x{2049}|\x{3030}|\x{303D}|\x{2139}|\x{2122}|\x{3297}|\x{3299}][\x{FE00}-\x{FEFF}]?|[\x{2190}-\x{21FF}][\x{FE00}-\x{FEFF}]?|[\x{2300}-\x{23FF}][\x{FE00}-\x{FEFF}]?|[\x{2460}-\x{24FF}][\x{FE00}-\x{FEFF}]?|[\x{25A0}-\x{25FF}][\x{FE00}-\x{FEFF}]?|[\x{2600}-\x{27BF}][\x{FE00}-\x{FEFF}]?|[\x{2900}-\x{297F}][\x{FE00}-\x{FEFF}]?|[\x{2B00}-\x{2BF0}][\x{FE00}-\x{FEFF}]?|[\x{1F000}-\x{1F6FF}][\x{FE00}-\x{FEFF}]?/u', '', $text);
    }

}
