<?php

namespace Sync\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Railken\Mangafox\Mangafox;
use Core\Manga\MangaManager;
use Illuminate\Support\Facades\Storage;
use Cocur\Slugify\Slugify;

class IndexMangaJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $uid;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($uid)
    {
        $this->uid = $uid;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $this->mangafox = new Mangafox();
        $this->manager = new MangaManager();

        $result = $this->mangafox
        ->resource($this->uid)
        ->get();

        $entity = $this->manager->updateOrCreate(['mangafox_uid' => $result->uid], [
            'title' => $result->name,
            'slug' => (new Slugify())->slugify($result->name),
            'artist' => $result->artist,
            'author' => $result->author,
            'aliases' => $result->aliases,
            'genres' => $result->genres,
            'released_year' => $result->released_year,
            'status' => $result->status,
            'overview' => $result->description,
            'mangafox_url' => $result->url,
            'mangafox_uid' => $result->uid
        ]);

        $entity = $entity->getResource();

        $ext = pathinfo(strtok($result->cover, '?'), PATHINFO_EXTENSION);

        Storage::put('public/manga-covers/'.$entity->id.'/'.md5($entity->id).".".$ext, file_get_contents($result->cover));
        

    }

}