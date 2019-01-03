<?php

namespace App\Jobs;

use Cocur\Slugify\Slugify;
use Core\Manga\MangaManager;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Railken\Mangafox\Mangafox;

class IndexMangaJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $id;

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

        try {
            $result = $this->mangafox
            ->resource($this->uid)
            ->get();
        } catch (Exception $e) {
            $this->failed($e);

            return;
        }

        $entity = $this->manager->updateOrCreate(['mangafox_uid' => $result->uid], [
            'aliases'       => $result->aliases,
            'genres'        => $result->genres,
            'status'        => $result->status,
            'overview'      => $result->description,
        ]);

        $entity = $entity->getResource();

        $ext = pathinfo(strtok($result->cover, '?'), PATHINFO_EXTENSION);

        Storage::disk('s3')->put("public/manga/{$entity->slug}/covers/cover.{$ext}", file_get_contents($result->cover));
    }
}
