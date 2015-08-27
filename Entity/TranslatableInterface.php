<?php

namespace Leapt\I18nBundle\Entity;

/**
 * Interface TranslatableInterface
 *
 * Implement this interface when you want your entity to be translatable
 *
 * @package Leapt\I18nBundle\Entity
 */
interface TranslatableInterface 
{
    /**
     * Method used to retrieve the localized translation
     *
     * @param $locale
     * @return TranslationInterface
     */
    public function getTranslation($locale);
}