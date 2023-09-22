<?php

namespace Modules\Base\Traits;

use Illuminate\Support\Carbon;
use Camroncade\Timezone\Facades\Timezone;

trait FormattedDate
{
    /**
     * Format datetime by field
     * 
     * @return string|DateTime
     */
    public function formattedDatetime($fieldName) 
    {
        if(auth()->check()) {
            return Carbon::parse(
                Timezone::convertFromUTC(
                    $fieldName, 
                    auth()->user()->timezone
                )
            )->format(auth()->user()->date_format);
        }

        return $fieldName;
    }

    /**
     * Format created at field
     * 
     * @return string|DateTime
     */
	public function formattedCreatedAt() 
    {
       return $this->formattedDatetime($this->created_at);
    }

    /**
     * Format created at field
     * 
     * @return string|DateTime
     */
    public function formattedUpdatedAt() 
    {
       return $this->formattedDatetime($this->updated_at);
    }

    /**
     * Format deleted at field
     * 
     * @return string|DateTime
     */
    public function formattedDeletedAt() 
    {
       return $this->formattedDatetime('deleted_at');
    }
}