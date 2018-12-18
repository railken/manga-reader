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
use Exception;

class IndexMangaJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $uid;
    protected $logger;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($uid)
    {
        $this->uid = $uid;
        $this->logger = new \Core\Log\LogService();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $parent = $this->logger->log("info", "manga:sync:index", "Retrieving info about {$this->uid}");

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
            'mangafox_uid' => $result->uid,
            'mangafox_id' => $result->id,
        ]);

        $entity = $entity->getResource();

        $ext = pathinfo(strtok($result->cover, '?'), PATHINFO_EXTENSION);

        try {
            Storage::disk('s3')->put("public/manga/{$entity->slug}/covers/cover.{$ext}", file_get_contents($result->cover));
        } catch (Exception $e) {
        }
    }

    /**
     * The job failed to process.
     *
     * @param  Exception  $exception
     *
     * @return void
     */
    public function failed(Exception $exception)
    {
        $parent = $this->logger->log("error", "manga:sync:index", "Error while retrieving info about {$this->uid}", [
            'exception' => [
                'class' => get_class($exception),
                'message' => $exception->getMessage()
            ]
        ]);
    }
}
