<?php

namespace Modules\Photos\Http\Controllers\Web\Admin;

use Illuminate\Http\Request;
use Modules\Photos\Models\Photo;
use Illuminate\Contracts\Support\Renderable;
use Modules\Base\Http\Controllers\Web\BaseController;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class GalleryListController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        return Media::with(['model'])
            ->where('model_type', 'Modules\Photos\Models\Photo')
            ->when(request('folder'), function ($query, $folderId) {
                $query->when($folderId !== 'all', function ($query) use ($folderId) {
                    $query->where('model_id', $folderId);
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(8)
            ->through(function ($media, $key) {
                if ($media->hasGeneratedConversion('preview')) {
                    $media->generated_preview_url = $media->preview_url;
                } else {
                    $media->generated_preview_url = $media->original_url;
                }
                return $media;
            });
    }
}
