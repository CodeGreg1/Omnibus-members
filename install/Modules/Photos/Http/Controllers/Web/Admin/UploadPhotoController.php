<?php

namespace Modules\Photos\Http\Controllers\Web\Admin;

use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Modules\Photos\Events\PhotoUploaded;
use Illuminate\Contracts\Support\Renderable;
use Modules\Photos\Repositories\PhotosRepository;
use Modules\Photos\Http\Requests\UploadPhotoRequest;
use Modules\Base\Http\Controllers\Web\BaseController;

class UploadPhotoController extends BaseController
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
     * Store images in storage.
     * @param UploadPhotoRequest $request
     * @return Renderable
     */
    public function handle(UploadPhotoRequest $request)
    {
        $folder = $this->photos->findOrFail($request->get('folder'));

        try {
            Storage::allDirectories();
        } catch (\Exception $e) {
            report($e);
            return $this->errorResponse($e->getMessage());
        }

        foreach ($request->file('photos') as $entry) {
            if ($entry->isValid()) {
                $file = $entry->getClientOriginalName();
                $name = pathinfo($file, PATHINFO_FILENAME);
                // $ext = pathinfo($file, PATHINFO_EXTENSION);
                $filename = md5(Str::uuid());
                $remporaryPath = "temporary-uploads/$filename.webp";

                $imageObj = Image::make($entry);

                Storage::disk('local')->put(
                    $remporaryPath,
                    (string) $imageObj->encode('webp')
                );
                $imageFile = new UploadedFile(
                    storage_path("app/$remporaryPath"),
                    basename(storage_path("app/$remporaryPath"))
                );

                $media = $folder->addMedia($imageFile)
                    ->usingName($name)
                    ->usingFileName("$filename.webp")
                    ->withCustomProperties([
                        'width' => $imageObj->getWidth(),
                        'height' => $imageObj->getHeight(),
                    ])
                    ->toMediaCollection('photos');

                Storage::delete($remporaryPath);

                event(new PhotoUploaded($media));
            }
        }

        $redirectTo = $request->has('redirectTo')
            ? $request->redirectTo
            : route('admin.photos.index');

        return $this->successResponse(__('Photo uplaoded successfully.'), [
            'redirectTo' => $redirectTo
        ]);
    }
}
