<?php

namespace Core\Manga;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Railken\Laravel\Manager\Contracts\EntityContract;

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
	protected $fillable = ['name'];

	/**
	 * The attributes that should be mutated to dates.
	 *
	 * @var array
	 */
	protected $dates = ['deleted_at'];
}