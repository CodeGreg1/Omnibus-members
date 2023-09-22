<?php

namespace Modules\Photos\Http\Controllers\Web\Admin;

use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Renderable;
use Spatie\MediaLibrary\Support\MediaStream;
use Modules\Base\Http\Controllers\Web\BaseController;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class DownloadPhotoController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function handle(Request $request)
    {
        $images = explode(",", $request->get('images'));
        $downloads = Media::with(['model'])->whereIn('id', $images)->get();

        return MediaStream::create('images.zip')->addMedia($downloads);
    }
}
