<?php

namespace Issei\ConfirmableForm;

use Symfony\Component\Form\AbstractExtension;

/**
 * @author Issei Murasawa <issei.m7@gmail.com>
 */
class ConfirmableFormExtension extends AbstractExtension
{
    /**
     * {@inheritdoc}
     */
    protected function loadTypes()
    {
        return [
            new Type\OperationType(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function loadTypeExtensions()
    {
        return [
            new Type\FormTypeOperationExtension(),
        ];
    }
}
