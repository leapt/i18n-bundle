<?php

namespace Leapt\I18nBundle\Routing;

use Leapt\I18nBundle\Registry;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Routing\RequestContext;

/**
 * Class I18nRouter
 * @package Leapt\I18nBundle\Routing
 */
class I18nRouter extends Router
{
    /**
     * @var \Leapt\I18nBundle\Registry
     */
    protected $registry;

    /**
     * @param Registry $registry
     */
    public function setRegistry(Registry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * @param string $name
     * @param array $parameters
     * @param bool|string $referenceType
     * @throws \UnexpectedValueException
     * @return string
     */
    public function generate($name, $parameters = array(), $referenceType = self::ABSOLUTE_PATH)
    {
        $originalParameters = $parameters;
        try {
            if(isset($parameters['_locale'])) {
                $locale = $parameters['_locale'];
            }
            else {
                $locale = $this->getContext()->getParameter('_locale');
            }
            if(!in_array($locale, $this->registry->getRegisteredLocales())) {
                throw new \UnexpectedValueException(sprintf('The locale %s has not been registered in the leapt_i18n config', $locale));
            }
            $i18nName = $name . '.' . $locale;
            unset($parameters['_locale']);

            return parent::generate($i18nName, $parameters, $referenceType);
        }
        catch (RouteNotFoundException $e) {
            return parent::generate($name, $originalParameters, $referenceType);
        }
    }

    /**
     * @param string $pathinfo
     * @return array
     */
    public function match($pathinfo)
    {
        $match = parent::match($pathinfo);

        return $this->processMatch($match);
    }

    /**
     * @param Request $request
     * @return array
     */
    public function matchRequest(Request $request)
    {
        $match = parent::matchRequest($request);

        return $this->processMatch($match);
    }

    /**
     * Process the match array so that if it contains a _locale parameter,
     * we remove the .locale suffix that is appended to each route in the i18n routing system
     *
     * @param array $match
     * @return array
     */
    private function processMatch(array $match) {
        if (
            !empty($match['_locale']) &&
            preg_match('#^(.+)\.' . preg_quote($match['_locale'], '#') . '+$#', $match['_route'], $matches)
        ) {
            $match['_route'] = $matches[1];
        }

        return $match;
    }
}
