<?php

namespace Modules\Pages\Services;

use Modules\Pages\Models\PageSection;

interface SectionInterface
{
    public function get(PageSection $section);
}