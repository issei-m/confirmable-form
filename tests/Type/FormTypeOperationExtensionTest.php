<?php

namespace Issei\ConfirmableForm\Tests\Type;

use Issei\ConfirmableForm\CompatibilityHelper;
use Symfony\Component\Form\FormInterface;

class FormTypeOperationExtensionTest extends TypeTestCase
{
    /**
     * @var FormInterface
     */
    private $form;

    protected function setUp()
    {
        parent::setUp();

        $formType = CompatibilityHelper::isFormLegacy()
            ? 'form'
            : 'Symfony\Component\Form\Extension\Core\Type\FormType'
        ;

        $hiddenType = CompatibilityHelper::isFormLegacy()
            ? 'hidden'
            : 'Symfony\Component\Form\Extension\Core\Type\HiddenType'
        ;

        $this->form = $this->factory->createBuilder($formType, null, ['confirmable' => true])
            ->add('foo', $hiddenType)
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
