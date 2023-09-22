<?php

namespace Modules\Photos\Http\Controllers\Web\Admin;

use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Renderable;
use Modules\Photos\Events\PhotoFolderCreated;
use Modules\Photos\Events\PhotoFolderDeleted;
use Modules\Photos\Events\PhotoFolderUpdated;
use Modules\Photos\Repositories\PhotosRepository;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\Photos\Http\Requests\StorePhotoFolderRequest;
use Modules\Photos\Http\Requests\UpdatePhotoFolderRequest;

class PhotoController extends BaseController
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
    public function index()
    {
        return view('photos::admin.index', [
            'pageTitle' => __('Photos'),
            'folders' => $this->photos->getAll()
        ]);
    }

    public function folders()
    {
        return $this->photos->getAll();
    }

    /**
     * Store a newly created resource in storage.
     * @param StorePhotoFolderRequest $request
     * @return Renderable
     */
    public function store(StorePhotoFolderRequest $request)
    {
        $model = $this->photos->create($request->only([
            'name',
            'description'
        ]));

        event(new PhotoFolderCreated($model));

        return $this->successResponse(__('Folder created successfully.'), [
            'redirectTo' => route('admin.photos.index'),
            'folder' => $model
        ]);
    }

    /**
     * Update the specified resource in storage.
     * @param UpdatePhotoFolderRequest $request
     * @param int $id
     * @return Renderable
     */
    public function update(UpdatePhotoFolderRequest $request, $id)
    {
        $model = $this->photos->findOrFail($id);

        $this->photos->update(
            $model,
            $request->only(['name', 'description'])
        );

        event(new PhotoFolderUpdated($model->fresh()));

        return $this->handleAjaxRedirectResponse(__('Folder updated successfully.'), route('admin.photos.index'));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $folder = $this->photos->findOrFail($id);

        $result = $this->photos->delete($folder);
        event(new PhotoFolderDeleted($folder));

        return $this->successResponse('Folder successfully deleted', [
            'redirectTo' => route('admin.photos.index')
        ]);
    }
}
