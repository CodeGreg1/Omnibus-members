<?php

namespace Modules\Photos\Http\Controllers\Web\Admin;

use Illuminate\Http\Request;
use Modules\Photos\Events\PhotoDeleted;
use Illuminate\Contracts\Support\Renderable;
use Modules\Photos\Repositories\PhotosRepository;
use Modules\Base\Http\Controllers\Web\BaseController;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class DeletePhotoController extends BaseController
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
    public function handle(Request $request)
    {
        $this->authorize('admin.photos.images.destroy');

        foreach ($request->get('images') as $image) {
            $model = Media::findOrFail($image);

            $model->delete();

            event(new PhotoDeleted($model));
        }

        return $this->successResponse(__('Selected images successfully removed.'));
    }
}
