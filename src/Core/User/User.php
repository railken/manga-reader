<?php

namespace Core\User;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Railken\Laravel\Manager\Contracts\EntityContract;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable implements EntityContract
{
    use HasApiTokens, Notifiable, SoftDeletes;

    const ROLE_USER = 'user';
    const ROLE_ADMIN = 'admin';
    
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

	/**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public function setPasswordAttribute($pass)
    {
        $this->attributes['password'] = bcrypt($pass);
    }
    

    /**
     * Return if has role user
     *
     * @return bool
     */
    public function isRoleUser()
    {
        return $this->role == static::ROLE_USER;
    }

    /**
     * Return if has role admin
     *
     * @return bool
     */
    public function isRoleAdmin()
    {
        return $this->role == static::ROLE_ADMIN;
    }
}
