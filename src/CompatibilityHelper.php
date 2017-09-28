<?php

namespace Issei\ConfirmableForm;

/**
 * @author Issei Murasawa <issei.m7@gmail.com>
 */
final class CompatibilityHelper
{
    private static $isFormLegacy;

    /**
     * Returns true if legacy (older than 2.8, not support FQCN passing) version of symfony/form component is installed.
     *
     * @return bool
     */
    public static function isFormLegacy()
    {
        if (null === self::$isFormLegacy) {
            self::$isFormLegacy = !(new \ReflectionClass('Symfony\Component\Form\AbstractType'))->hasMethod('getBlockPrefix');
        }

        return self::$isFormLegacy;
    }

    private function __construct()
    {
    }
}
