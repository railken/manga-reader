<?php

namespace Core\Manga;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Railken\Laravel\Manager\Contracts\EntityContract;
use Core\Chapter\Chapter;

class Manga extends Model implements EntityContract
{

	use SoftDeletes;

	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'manga';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'title', 
		'overview', 
		'aliases',
		'mangafox_url', 
		'mangafox_uid', 
		'mangafox_id', 
		'artist', 
		'author', 
		'aliases', 
		'genres', 
		'released_year', 
		'status', 
		'slug'
	];

	/**
	 * The attributes that should be mutated to dates.
	 *
	 * @var array
	 */
	protected $dates = [
		'deleted_at'
	];
	
	/**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'aliases' => 'array',
        'genres' => 'array',
    ];

    public function chapters()
    {
        return $this->hasMany(Chapter::class, 'manga_id');
    }

}
