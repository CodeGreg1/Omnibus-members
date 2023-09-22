<?php

namespace Modules\Photos\Repositories;

use Modules\Photos\Models\Photo;
use Modules\Base\Repositories\BaseRepository;

class PhotosRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    protected $model = Photo::class;

    public function getAll()
    {
        return $this->getModel()->all()->map(function ($folder) {
            $image = $folder->media()->first();

            return (object) [
                'id' => $folder->id,
                'name' => $folder->name,
                'description' => $folder->description,
                'image' => $image ? $image->preview_url : '/upload/media/default/preview.jpg',
                'total_images' => $folder->media()->count()
            ];
        });
    }

    public function getCollection($entity)
    {
        return $entity->media()->with(['model'])->paginate(50);

        // return $this->getModel()->where('id', $entity->id)->map(function ($gallery) {
        //     return (object) [
        //         'id' => $gallery->id,
        //         'name' => $gallery->name,
        //         'description' => $gallery->description,
        //         'image' => '/upload/media/default/preview.jpg',
        //         'total_images' => 0
        //     ];
        // });
    }
}
