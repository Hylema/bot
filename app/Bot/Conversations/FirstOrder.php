<?php

namespace App\Bot\Conversations;

use App\Models\DriverLicense;
use App\Models\Passport;
use App\Models\VehicleRegistrationCertificate;
use App\User;

/**
 * Class FirstOrder
 *
 * Первый заказ пользователя
 *
 * @package App\Bot\Conversations
 */
class FirstOrder extends OsagoBase
{
    public function processMessage($message)
    {
        switch ($this->firstOrder) {
            case 'getFullName':
                $this->getFullName($message);
                break;
            case 'getPassport':
                $this->getPassport($message);
                break;
            case 'messageIsPassportPhoto':
                $this->messageIsPassportPhoto($message);
                break;
            case 'getVRCFrontSide':
                $this->getVRCFrontSide($message);
                break;
            case 'getVRCBackSide':
                $this->getVRCBackSide($message);
                break;
            case 'getNumberOfDrivers':
                $this->getNumberOfDrivers($message);
                break;
            case 'setUnlimitedDrivers':
                $this->setUnlimitedDrivers();
                break;
            case 'setLimitedDrivers':
                $this->setLimitedDrivers($message);
                break;
            case 'addDriverLicenseFrontSide':
                $this->addDriverLicenseFrontSide($message);
                break;
            case 'addDriverLicenseBackSide':
                $this->addDriverLicenseBackSide($message);
                break;
            default:
                $this->sendText('Что-то пошло не так');
        }
    }
    
    public function getFullName($message)
    {
        $user = User::find($message->user_id);
        $user->full_name = $message->text;
        $user->save();

        $this->sendText('Пришлите фото паспорта');
        $this->firstOrder = 'getPassport';
    }
    
    public function getPassport($message)
    {
        if ($this->messageIsPhoto($message->body)) {

            Passport::create([
                'photo_path' => $message->body,
                'user_id' => $message->user_id,
                'status' => 'processing',
            ]);

            $this->sendText('Это фотография паспорта?' . PHP_EOL . '1. Да.' . PHP_EOL . '2. Нет.');
            $this->firstOrder = 'messageIsPassportPhoto';
        } else {
            $this->sendText('Пришлите фото паспорта');
        }
    }
    
    public function messageIsPassportPhoto($message)
    {
        switch (trim($message->text)) {
            case '1':
                $this->sendText('Пришлите фото лицевой и фото обратной стороны стс');
                $this->firstOrder = 'getVRCFrontSide';
                break;
            case '2':
                $this->firstOrder = 'getPassport';
                break;
            default:
                $this->sendText('Это фотография паспорта?' . PHP_EOL . '1. Да' . PHP_EOL . '2. Нет');
        }
    }
    
    public function getVRCFrontSide($message)
    {
        if($this->messageIsPhoto($message->body)) {

            VehicleRegistrationCertificate::create([
                'photo_path' => json_encode($message->body),
                'user_id' => $message->user_id,
                'status' => 'processing',
            ]);

            $this->firstOrder = 'getVRCBackSide';
        } else {
            $this->sendText('Пришлите фото лицевой и фото обратной стороны стс');
        }
    }
    
    public function getVRCBackSide($message)
    {
        if($this->messageIsPhoto($message->body)) {
            $VRC = VehicleRegistrationCertificate::find($message->user_id);

            if ($VRC) {
                $firstPhoto = json_decode($VRC->photo_path);

                $VRC->photo_path = json_encode([$firstPhoto, $message->body]);
                $VRC->save();

                $this->sendText('Выберите количество водителей: ' . PHP_EOL . '1. Несколько' . PHP_EOL . '2. Неограниченно');
                $this->firstOrder = 'getNumberOfDrivers';
            } else {
                $this->sendText('Пришлите фото лицевой и фото обратной стороны стс');
                $this->bot->getVRCFrontSide();
            }
        } else {
            $this->sendText('Пришлите недостающее фото');
        }
    }
    
    public function getNumberOfDrivers($message)
    {
        switch ($message->text) {
            case '1':
                $this->sendText('Выберите действие:' . PHP_EOL . '1. Добавить' . PHP_EOL . '2. Закончить');
                $this->firstOrder = 'setLimitedDrivers';
                break;
            case '2':
                $this->sendText('Ваш заказ обрабатывается');
                $this->firstOrder = 'setUnlimitedDrivers';
                break;
            default:
                $this->sendText('Выберите количество водителей:' . PHP_EOL . '1. Несколько' . PHP_EOL . '2. Неограниченно');
                break;
        }
    }
    
    public function setUnlimitedDrivers()
    {
        $this->confirm();
    }
    
    public function setLimitedDrivers($message)
    {
        switch ($message->text){
            case '1':
                $this->sendText('Пришлите фото лицевой и фото обратной стороны водительских прав');
                $this->firstOrder = 'addDriverLicenseFrontSide';
                break;
            case '2':
                $this->sendText('Ваш заказ обрабатывается');
                $this->confirm();
                break;
            default:
                $this->sendText('выберите действие:' . PHP_EOL . '1. Добавить' . PHP_EOL . '2. Закончить');
                break;
        }
    }
    
    public function addDriverLicenseFrontSide($message)
    {
        if($this->messageIsPhoto($message->body)) {

            DriverLicense::create([
                'photo_path' => json_encode([$message->body]),
                'user_id' => $message->user_id,
                'status' => 'processing',
            ]);

            $this->firstOrder = 'addDriverLicenseBackSide';
        } else {
            $this->sendText('Пришлите фото лицевой и фото обратной стороны водительских прав');
        }
    }
    
    public function addDriverLicenseBackSide($message)
    {
        if($this->messageIsPhoto($message->body)) {
            $driverLicense = DriverLicense::find($message->user_id);

            if ($driverLicense) {
                $photo_path = json_decode($driverLicense->photo_path);
                $driverLicenseId = $driverLicense->id;
                if (count($photo_path) > 1) {
                    $nextDriverLicenseId = DriverLicense::where('id', '>', $driverLicenseId)->min('id');
                    $driverLicense = DriverLicense::find($nextDriverLicenseId);
                    $photo_path = json_decode($driverLicense->photo_path);
                }
                array_push($photo_path, $message->body);
                $driverLicense->photo_path = json_encode($photo_path);
                $driverLicense->save();

                $this->sendText('Выберите действие:' . PHP_EOL . '1. Добавить' . PHP_EOL . '2. Закончить');
                $this->firstOrder = 'setLimitedDrivers';
            } else {
                $this->sendText('Пришлите фото лицевой и фото обратной стороны водительских прав');
            }
        } else {
            $this->sendText('Пришлите недостающее фото');
        }
    }
    
    public function messageIsPhoto($message)
    {
        if (!empty($message)) {
            return true;
        }
        return false;
    }
    
    public function confirm()
    {
        $this->firstOrder = '';
        $this->userIsWelcomed = false;
        $this->conversation = 'default';
    }
}
