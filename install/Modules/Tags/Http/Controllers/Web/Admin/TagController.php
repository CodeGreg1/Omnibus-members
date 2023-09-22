<?php

namespace Modules\Tags\Http\Controllers\Web\Admin;

use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Renderable;
use Modules\Tags\Repositories\TagsRepository;
use Modules\Base\Http\Controllers\Web\BaseController;

class TagController extends BaseController
{
    /**
     * @var TagsRepository
     */
    public $tags;

    public function __construct(TagsRepository $tags)
    {
        parent::__construct();

        $this->tags = $tags;
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $tag = $this->tags->firstOrCreate(['name' => $request->get('name')], []);

        return $this->successResponse(__('Tag created successfully.'), [
            'tag' => $tag
        ]);
    }

    /**
     * Search specific name
     * @param Request $request
     */
    public function search(Request $request)
    {
        return $this->tags->search($request->get('name'));
    }
}
