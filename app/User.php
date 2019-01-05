<?php

namespace App;

use App\Models\Message;
use App\Models\UserMessengers;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'phone_number',
        'first_name',
        'last_name',
        'middle_name',
        'birthday',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    /**
     *
     * Ищет пользователя по идентификатору мессенджера, создает при необходимости и возвращает объект пользователя.
     *
     * @param $id
     * @param $messenger
     * @return User
     */
    public static function findUserByMessengerId($id, $messenger) {
        $userMessenger = UserMessengers::firstOrNew([
            'messenger_user_id' => $id,
            'messenger' => $messenger
        ]);

        if ($userMessenger->user_id == 0) {
            $user = new User();
            $user->save();
            $userMessenger->user_id = $user->id;
            $userMessenger->save();
        } else {
            $user = $userMessenger->user()->first();
        }
        return $user;
    }
}
