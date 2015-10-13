<?php

namespace Issei\ConfirmableForm\Tests\Type;

use Issei\ConfirmableForm\ConfirmableFormExtension;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Test\TypeTestCase;

class FormTypeOperationExtensionTest extends TypeTestCase
{
    /**
     * @var FormInterface
     */
    private $form;

    protected function getExtensions()
    {
        return [
            new ConfirmableFormExtension(),
        ];
    }

    protected function setUp()
    {
        parent::setUp();

        $this->form = $this->factory->createBuilder('form', null, ['confirmable' => true])
            ->add('foo', 'hidden')
            ->getForm()
        ;
    }

    public function testFields()
    {
        $this->assertCount(2, $this->form);
        $this->assertTrue($this->form->has('_operation'));

        $view = $this->form->createView();
        $this->assertCount(1, $view['_operation']);
        $this->assertArrayHasKey('confirm', $view['_operation']);
    }

    public function testProgressToConfirm()
    {
        $this->form->submit([
            '_operation' => [
                'confirm' => '',
            ],
        ]);

        $view = $this->form->createView();
        $this->assertCount(2, $view['_operation']);
        $this->assertArrayHasKey('back', $view['_operation']);
        $this->assertArrayHasKey('commit', $view['_operation']);
    }
}
