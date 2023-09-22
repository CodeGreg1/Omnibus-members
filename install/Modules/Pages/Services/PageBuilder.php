<?php

namespace Modules\Pages\Services;

use Illuminate\Support\Arr;
use Modules\Pages\Models\Page;
use Nwidart\Modules\Facades\Module;

class PageBuilder
{
    /**
     * the available sections
     *
     * @var
     */
    protected $sections = [];

    public function build(Page $page)
    {
        $html = [];

        if ($page->type === 'section') {
            foreach ($page->sections as $section) {
                $sectionBuilder = $this->getSection($section->template);
                if ($sectionBuilder && class_exists($sectionBuilder->builder_class)) {
                    try {
                        $html[] = (new $sectionBuilder->builder_class)->get($section);
                    } catch (\Exception $e) {
                        report($e);
                    }
                }
            }
        } else {
            if ($page->content) {
                $html[] = '<section class="page-article"><div class="container"><article class="article article-style-d article-detail"><div class="article-description">' . $page->content->body . '</div></article></div></section>';
            }
        }

        return implode("", $html);
    }

    public function getSection($template)
    {
        return Arr::first($this->sections, function ($item) use ($template) {
            return $item->template === $template;
        });
    }

    /**
     * Set sections.
     *
     * @param array $sections
     * @return mixed
     */
    public function sections(array $sections)
    {
        foreach ($sections as $section) {
            if ($section instanceof PageSectionBuilder) {
                $this->sections[] = $section;
            }
        }
    }

    public function getSections()
    {
        return collect($this->sections)->sortBy(function (PageSectionBuilder $section, int $key) {
            return !$section->default;
        })->toArray();
    }

    protected function hasModule($modules)
    {
        if (!$modules) {
            return true;
        }

        foreach ($modules as $module) {
            if (!Module::has($module)) {
                return false;
            }

            if (Module::isDisabled($module)) {
                return false;
            }
        }

        return true;
    }
}