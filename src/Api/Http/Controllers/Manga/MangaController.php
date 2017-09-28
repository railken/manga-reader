<?php

namespace Api\Http\Controllers\Manga;

use Api\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Core\Manga\MangaManager;

class MangaController extends Controller
{

    /**
     * Construct
     *
     */
    public function __construct(MangaManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * Display current user
     *
     * @param Request $request
     *
     * @return response
     */
    public function index(Request $request)
    {
        die();
    }
}
