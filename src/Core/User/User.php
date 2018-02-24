<?php

namespace Core\User;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;
use Railken\Laravel\Manager\Contracts\EntityContract;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use Core\Manga\Manga;

class User extends Authenticatable implements EntityContract
{
    use HasApiTokens, Notifiable;
    
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
    protected $dates = [];

    /**
     * Library relation
     */
    public function library()
    {
        return $this->belongsToMany(Manga::class, 'libraries', 'user_id', 'manga_id');
    }

    /**
     * Pending email
     *
     * @return Relation
     */
    public function pendingEmail()
    {
        return $this->hasOne(UserPendingEmail::class, 'user_id')->latest();
    }

    /**
     * Has manga in library
     *
     * @param Manga $manga
     *
     * @return void
     */
    public function hasMangaInLibrary(Manga $manga)
    {
        return $this->library()->where('manga_id', $manga->id)->first();
    }

    /**
     * Set password attribute
     *
     * @param string $pass
     *
     * @return void
     */
    public function setPasswordAttribute($pass)
    {
        $this->attributes['password'] = bcrypt($pass);
    }

    /**
     * Retrieve user for passport oauth
     *
     * @param string $identifier
     *
     * @return User
     */
    public function findForPassport($identifier)
    {
        return User::orWhere(function ($q) use ($identifier) {
            return $q->orWhere('email', $identifier)->orWhere('username', $identifier);
        })->where('enabled', 1)->first();
    }
}
