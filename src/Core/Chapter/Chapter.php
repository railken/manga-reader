<?php

namespace Core\Chapter;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Railken\Laravel\Manager\Contracts\EntityContract;
use Core\Manga\Manga;
use Illuminate\Support\Facades\Storage;

class Chapter extends Model implements EntityContract
{

	use SoftDeletes;

	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'chapters';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['id', 'number', 'volume', 'title', 'slug', 'scans', 'manga_id', 'released_at'];

	/**
	 * The attributes that should be mutated to dates.
	 *
	 * @var array
	 */
	protected $dates = [
		'released_at', 
		'deleted_at'
	];

    /**
     * Chapter N -> 1 Manga
     *
     * @return Relation
     */
    public function prev()
    {
        return Chapter::where('manga_id', $this->manga_id)->where(\DB::raw('CAST(number as DECIMAL(10,5))'), '<', (float)$this->number)->orderBy(\DB::raw('CAST(number as DECIMAL(10,5))'), 'DESC');
    }


    /**
     * Chapter N -> 1 Manga
     *
     * @return Relation
     */
    public function next()
    {
        return Chapter::where('manga_id', $this->manga_id)->where(\DB::raw('CAST(number as DECIMAL(10,5))'), '>', (float)$this->number)->orderBy(\DB::raw('CAST(number as DECIMAL(10,5))'), 'ASC');
    }


    /**
     * Chapter N -> 1 Manga
     *
     * @return Relation
     */
    public function manga()
    {
        return $this->belongsTo(Manga::class);
    }

    /**
     * Get path chapter
     *
     * @return string
     */
    public function getPathChapter()
    {   
        $volume = $this->volume == "-1" ? $this->volume : str_pad($this->volume, 5, '0', STR_PAD_LEFT);
        $number = str_pad($this->number, 5, '0', STR_PAD_LEFT);

        return "public/manga/{$this->manga->slug}/chapters/{$volume}/{$number}";
    }

    /**
     * Get resources
     *
     * @return Collection
     */
    public function getResourcesAttribute()
    {
        
        $resources = collect();

        if (!$this->scans)
            return $resources;

        $filename = $this->getPathChapter();

    	
    	foreach (Storage::allFiles($filename) as $resource) {
    		$resources[] = env("APP_URL").Storage::url($resource);
    	}	

    	return $resources;

    }
}
