<?php

namespace Issei\ConfirmableForm\Tests\Type;

use Issei\ConfirmableForm\CompatibilityHelper;

class FormTypeOperationExtensionTest extends TypeTestCase
{
    public function testFields()
    {
        $form = $this->factory->createBuilder(self::getCompoundType(), null, ['confirmable' => true])
            ->add('foo', self::getNonCompoundType())
            ->getForm()
        ;
        
        $this->assertCount(2, $form);
        $this->assertTrue($form->has('_operation'));
        $this->assertEquals('Confirm', $form->get('_operation')->get('confirm')->getConfig()->getOption('label'));
        $this->assertEquals('Back', $form->get('_operation')->get('back')->getConfig()->getOption('label'));
        $this->assertEquals('Send', $form->get('_operation')->get('commit')->getConfig()->getOption('label'));

        $view = $form->createView();
        $this->assertCount(1, $view['_operation']);
        $this->assertArrayHasKey('confirm', $view['_operation']);
    }

    public function testProgressToConfirm()
    {
        $form = $this->factory->createBuilder(self::getCompoundType(), null, ['confirmable' => true])
            ->add('foo', self::getNonCompoundType())
            ->getForm()
        ;
        
        $form->submit([
            '_operation' => [
                'confirm' => '',
            ],
        ]);

        $view = $form->createView();
        $this->assertCount(2, $view['_operation']);
        $this->assertArrayHasKey('back', $view['_operation']);
        $this->assertArrayHasKey('commit', $view['_operation']);
    }

    public function testDisable()
    {
        $form = $this->factory->create(self::getCompoundType(), null, ['confirmable' => false]);

        $this->assertArrayNotHasKey('_operation', $form);
    }

    public function testButtonOptions()
    {
        $form = $this->factory->create(self::getCompoundType(), null, ['confirmable' => [
            'confirm_options' => ['label' => '確認'],
            'back_options' => ['label' => '戻る'],
            'commit_options' => ['label' => '送信'],
        ]]);

        $this->assertEquals('確認', $form->get('_operation')->get('confirm')->getConfig()->getOption('label'));
        $this->assertEquals('戻る', $form->get('_operation')->get('back')->getConfig()->getOption('label'));
        $this->assertEquals('送信', $form->get('_operation')->get('commit')->getConfig()->getOption('label'));
    }

    public function testWithNotCompoundForm()
    {
        $form = $this->factory->create(self::getNonCompoundType(), null, ['confirmable' => true]);
        $this->assertArrayNotHasKey('_operation', $form);
    }

    public function testWithNotParentForm()
    {
        $form = $this->factory->createBuilder(self::getCompoundType())
            ->add('foo', self::getCompoundType())
            ->getForm()
        ;
        $this->assertArrayNotHasKey('_operation', $form['foo']);
    }

    private static function getCompoundType()
    {
        return CompatibilityHelper::isFormLegacy()
            ? 'form'
            : 'Symfony\Component\Form\Extension\Core\Type\FormType'
        ;
    }

    private static function getNonCompoundType()
    {
        return CompatibilityHelper::isFormLegacy()
            ? 'hidden'
            : 'Symfony\Component\Form\Extension\Core\Type\HiddenType'
        ;
    }
}
