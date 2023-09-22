<?php

namespace Modules\Languages\Repositories;

use Modules\Languages\Models\Language;
use Modules\Base\Repositories\BaseRepository;

class LanguagesRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    protected $model = Language::class;

    /**
     * Get exclude english
     * 
     * @return \Illuminate\Database\Eloquent\Model|static
     */
    public function getExcludeEnglish() 
    {
        return (new $this->model)->where('code', '!=', 'en')->get();
    }

    /**
     * Get language by code
     * 
     * @param string $code
     * 
     * @return \Illuminate\Database\Eloquent\Model|static
     */
    public function getByCode($code) 
    {
        return $this->findBy('code', $code);
    }

    /**
     * Get languages by ids
     * 
     * @var array $ids
     * 
     * @return \Illuminate\Database\Eloquent\Model|static 
     */
    public function getByIds($ids) 
    {
        return (new $this->model)->whereIn('id', $ids)->get();
    }

}