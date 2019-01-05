<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'number',
        'series',
        'issuance_date',
        'photo_path',
        'user_id',
        'status',
    ];

    protected $casts = [
        'photo_path' => 'array'
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'id',
        'series',
        'number',
        'photo_path',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
