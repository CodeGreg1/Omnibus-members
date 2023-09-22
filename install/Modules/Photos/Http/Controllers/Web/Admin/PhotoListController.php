<?php

namespace Modules\Photos\Http\Controllers\Web\Admin;

use Illuminate\Contracts\Support\Renderable;
use Modules\Photos\Repositories\PhotosRepository;
use Modules\Base\Http\Controllers\Web\BaseController;

class PhotoListController extends BaseController
{
    /**
     * @var PhotosRepository
     */
    public $photos;

    public function __construct(PhotosRepository $photos)
    {
        parent::__construct();

        $this->photos = $photos;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function handle()
    {
        return view('photos::admin.list', [
            'pageTitle' => 'All photos',
            'folders' => $this->photos->all()
        ]);
    }
}
