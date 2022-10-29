<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 *  User Model.
 *
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property string $type
 * @property string $email
 * @property string $password
 * @property string $last_login
 */
class User extends Authenticatable
{
    use Notifiable;

    /**
     * @todo move user types to enums
     */
    public const BLOGGER_TYPE = 'Blogger';

    /**
     * @var string[]
     */
    protected $fillable = [
        'first_name', 'last_name', 'type', 'email', 'password', 'last_login'
    ];

    /**
     * @var string[]
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }
}
