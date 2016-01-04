<?php

namespace Leapt\I18nBundle;

/**
 * Class Registry
 * @package Leapt\I18nBundle
 */
class Registry
{
    /**
     * @var array
     */
    private $locales;

    /**
     * @var array An array of paths indexed by locale: Example array("fr" => "/fr/slug", "nl" => "/nl/slug")
     */
    private $switchPaths = [];

    /**
     * @param array $locales
     */
    public function __construct(array $locales)
    {
        $this->locales = $locales;
    }

    /**
     * @param array $locales
     */
    public function registerLocales(array $locales)
    {
        $this->locales = array_unique(array_merge($this->locales, $locales));
    }

    /**
     * @return array
     */
    public function getRegisteredLocales()
    {
        return $this->locales;
    }

    /**
     * Set the paths for the locale switcher (navigation-wise)
     *
     * @param array $paths an array of paths indexed by locale : Example array("fr" => "/fr/slug", "nl" => "/nl/slug")
     */
    public function setSwitchPaths(array $paths)
    {
        $this->switchPaths = $paths;
    }

    /**
     * Get the current locale switcher paths
     *
     * @return array an array of paths indexed by locale : Example array("fr" => "/fr/slug", "nl" => "/nl/slug")
     */
    public function getSwitchPaths()
    {
        return $this->switchPaths;
    }

    /**
     * @param string $locale
     * @param string $path
     */
    public function addSwitchPath($locale, $path)
    {
        $this->switchPaths[$locale] = $path;
    }
}