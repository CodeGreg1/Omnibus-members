<?php

namespace Modules\EmailTemplates\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmailTemplate extends Model
{
    use HasFactory;

    /**
     * @var string $table
     */
    protected $table = 'email_templates';

    /**
     * @var array $fillable
     */
    protected $fillable = [
        'code',
        'name',
        'subject',
        'content'
    ];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::creating(function ($model) {
            $model->code = email_template_code();
        });
    }
    
}
