<?php

namespace Modules\Pages\Services;

class SectionBuilder
{
    /**
     * @var \Modules\Pages\Services\SectionBuilderFactory
     */
    private $sectionServiceFactory;

    public function __get($name)
    {
        if (null === $this->sectionServiceFactory) {
            $this->sectionServiceFactory = new SectionBuilderFactory($this);
        }

        return $this->sectionServiceFactory->__get($name);
    }
}