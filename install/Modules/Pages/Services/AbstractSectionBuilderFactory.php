<?php

namespace Modules\Pages\Services;

abstract class AbstractSectionBuilderFactory
{
    /** @var \Modules\Pages\Models\PageSection */
    private $section;

    /** @var array<string, AbstractServiceFactory> */
    private $builders;

    /**
     * @param \Modules\Pages\Models\PageSection $section
     */
    public function __construct($section)
    {
        $this->section = $section;
        $this->builders = [];
    }

    /**
     * @param string $name
     *
     * @return null|string
     */
    abstract protected function getServiceClass($name);

    /**
     * @param string $name
     *
     * @return null|mixed
     */
    public function __get($name)
    {
        $serviceClass = $this->getServiceClass($name);

        if (null !== $serviceClass) {
            if (!\array_key_exists($name, $this->builders)) {
                $this->builders[$name] = new $serviceClass($this->section);
            }

            return $this->builders[$name];
        }

        \trigger_error('Undefined property: ' . static::class . '::$' . $name);

        return null;
    }
}