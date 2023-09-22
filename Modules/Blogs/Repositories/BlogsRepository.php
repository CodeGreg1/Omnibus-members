<?php

namespace Modules\Blogs\Repositories;

use Modules\Blogs\Models\Blog;
use Modules\Base\Repositories\BaseRepository;

class BlogsRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    protected $model = Blog::class;

    public function popular($take = 0, $blog = null)
    {
        $blogs = $this->getModel()
            ->where('status', 'published')
            ->when($blog, function ($query, $blog) {
                $query->where('id', '!=', $blog->id);
            })
            ->orderBy('views', 'desc');
        if ($take) {
            $blogs = $blogs->take($take);
        }

        return $blogs->get();
    }

    public function latest($take = 0, $blog = null)
    {
        $blogs = $this->getModel()
            ->where('status', 'published')
            ->when($blog, function ($query, $blog) {
                $query->where('id', '!=', $blog->id);
            })
            ->orderBy('id', 'desc');
        if ($take) {
            $blogs = $blogs->take($take);
        }

        return $blogs->get();
    }
}
