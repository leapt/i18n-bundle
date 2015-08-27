<?php

namespace Leapt\I18nBundle\Routing;

use Doctrine\Common\Annotations\Reader;
use Leapt\I18nBundle\Registry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Routing\AnnotatedRouteControllerLoader;
use Symfony\Component\Routing\RouteCollection;

/**
 * Class I18nAnnotatedRouteControllerLoader
 * @package Leapt\I18nBundle\Routing
 */
class I18nAnnotatedRouteControllerLoader extends AnnotatedRouteControllerLoader
{
    /**
     * @var string
     */
    protected $routeAnnotationClass = 'Leapt\\I18nBundle\\Annotation\\I18nRoute';

    /**
     * @var I18nLoaderHelper
     */
    private $helper;

    /**
     * @var \Leapt\I18nBundle\Registry
     */
    private $registry;

    /**
     * @param Reader $reader
     * @param I18nLoaderHelper $helper
     * @param Registry $registry
     */
    public function __construct(Reader $reader, I18nLoaderHelper $helper, Registry $registry)
    {
        parent::__construct($reader);

        $this->helper = $helper;
        $this->registry = $registry;
    }

    /**
     * @param mixed $resource
     * @param null $type
     * @return bool
     */
    public function supports($resource, $type = null)
    {
        return is_string($resource) && preg_match('/^(?:\\\\?[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)+$/', $resource) && (!$type || 'annotation_i18n' === $type);
    }

    /**
     * @param RouteCollection $collection
     * @param $annot
     * @param $globals
     * @param \ReflectionClass $class
     * @param \ReflectionMethod $method
     */
    protected function addRoute(
        RouteCollection $collection,
        $annot,
        $globals,
        \ReflectionClass $class,
        \ReflectionMethod $method
    ) {
        $i18n = isset($annot->data['i18n']) ? $annot->data['i18n'] : true;
        unset($annot->data['i18n']);

        foreach($this->registry->getRegisteredLocales() as $locale) {
            $i18nAnnot = new Route($annot->data);
            $i18nGlobals = $globals;

            if($i18n) {
                $i18nAnnot->setName($this->helper->alterName($i18nAnnot->getName(), $locale));
                $i18nAnnot->setPath($this->helper->alterPath($i18nAnnot->getPath(), $locale));
                $i18nAnnot->setDefaults($this->helper->alterDefaults($i18nAnnot->getDefaults(), $locale));

                if (isset($i18nGlobals['path']) && !empty($i18nGlobals['path'])) {
                    $i18nGlobals['path'] = '/' . $locale . '/' . ltrim($this->helper->alterPath($i18nGlobals['path'], $locale), '/');
                } else {
                    $i18nGlobals['path'] = '/' . $locale;
                }
            }

            parent::addRoute($collection, $i18nAnnot, $i18nGlobals, $class, $method);
        }
    }
}