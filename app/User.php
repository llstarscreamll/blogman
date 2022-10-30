<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
    public const SUPERVISOR_TYPE = 'Supervisor';
    public const ADMIN_TYPE = 'Admin';

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

    public function bloggers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'bloggers_and_supervisors', 'supervisor_id', 'blogger_id');
    }

    public function getNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function isBlogger(): bool
    {
        return $this->type === self::BLOGGER_TYPE;
    }

    public function isSupervisor(): bool
    {
        return $this->type === self::SUPERVISOR_TYPE;
    }
}
