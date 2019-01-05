<?php

namespace App\Bot\Conversations;

class OsagoBase extends Basic
{
    public function processAsync($processedDocuments, $user)
    {
        if (!empty($processedDocuments[$user->id]['passport'])) {
            $this->sendText('Ваш паспорт обработан!');
        }
        if (!empty($processedDocuments[$user->id]['driverLicense'])) {
            $this->sendText('Ваше водительское удостоверение обработано!');
        }
        if (!empty($processedDocuments[$user->id]['VRC'])) {
            $this->sendText('Ваш сертификат о регистрации обработан!');
        }
    }

}
