<?php

namespace App\Jobs;

use Cocur\Slugify\Slugify;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Railken\Mangafox\Mangafox;
use Railken\Amethyst\Managers\MangaManager;
use Railken\Amethyst\Managers\FileManager;

class DownloadCover implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $mangaId;
    protected $url;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($mangaId, $url)
    {
        $this->mangaId = $mangaId;
        $this->url = $url;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $mangaManager = new MangaManager();
        $fileManager = new FileManager();

        $manga = $mangaManager->getRepository()->findOneById($this->mangaId);

        $ext = pathinfo(strtok($this->url, '?'), PATHINFO_EXTENSION);

        $fileResult = $fileManager->uploadFileByContent(file_get_contents($this->url));
        $fileManager->assignToModel($fileResult->getResource(), $manga, ['tags' => ['cover']]);

    }
}
