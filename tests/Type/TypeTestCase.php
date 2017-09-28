<?php

namespace Issei\ConfirmableForm\Tests\Type;

use Issei\ConfirmableForm\ConfirmableFormExtension;
use Symfony\Component\Form\Test\TypeTestCase as BaseTestCase;

/**
 * @author Issei Murasawa <issei.m7@gmail.com>
 */
abstract class TypeTestCase extends BaseTestCase
{
    protected function getExtensions()
    {
        return [
            new ConfirmableFormExtension(),
        ];
    }
}
