<?php

namespace Modules\Pages\Models;

use Spatie\ModelStates\HasStates;
use Modules\Pages\States\StatusState;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Page extends Model
{
    use HasFactory, HasStates;

    protected $fillable = [
        "name",
        "description",
        "type",
        "slug",
        "status",
        "page_title",
        "page_description",
        "has_breadcrumb",
        "dark_mode"
    ];

    protected $casts = [
        'status' => StatusState::class
    ];

    /**
     * @var array TYPE_SELECT
     */
    public const TYPE_SELECT = [
        'section' => 'Section',
        'wysiwyg' => 'Wysiwyg'
    ];

    public function sections()
    {
        return $this->hasMany(PageSection::class);
    }

    public function content()
    {
        return $this->hasOne(PageContent::class, 'page_id');
    }
}
