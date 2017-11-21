<?php

namespace Issei\ConfirmableForm\Tests\Type;

use Issei\ConfirmableForm\CompatibilityHelper;

class OperationTypeTest extends TypeTestCase
{
    public function testFields()
    {
        $form = $this->factory->create(CompatibilityHelper::isFormLegacy() ? 'confirmable_form_operation' : 'Issei\ConfirmableForm\Type\OperationType');
        $this->assertCount(3, $form);

        $this->assertEquals('Confirm', $form->get('confirm')->getConfig()->getOption('label'));
        $this->assertEquals('Back', $form->get('back')->getConfig()->getOption('label'));
        $this->assertEquals('Commit', $form->get('commit')->getConfig()->getOption('label'));

        $view = $form->createView();

        // block prefix at 2nd from last
        $this->assertEquals('confirmable_form_operation', array_slice($view->vars['block_prefixes'], -2, 1)[0]);

        // children also
        foreach (['confirm', 'back', 'commit'] as $name) {
            $this->assertEquals('confirmable_form_operation_' . $name, array_slice($view[$name]->vars['block_prefixes'], -2, 1)[0]);
        }
    }

    public function testButtonOptions()
    {
        $form = $this->factory->create(CompatibilityHelper::isFormLegacy() ? 'confirmable_form_operation' : 'Issei\ConfirmableForm\Type\OperationType', null, [
            'confirm_options' => ['label' => '確認'],
            'back_options' => ['label' => '戻る'],
            'commit_options' => ['label' => '送信'],
        ]);

        $this->assertEquals('確認', $form->get('confirm')->getConfig()->getOption('label'));
        $this->assertEquals('戻る', $form->get('back')->getConfig()->getOption('label'));
        $this->assertEquals('送信', $form->get('commit')->getConfig()->getOption('label'));
    }

    public function testSubmit()
    {
        $form = $this->factory->create(CompatibilityHelper::isFormLegacy() ? 'confirmable_form_operation' : 'Issei\ConfirmableForm\Type\OperationType', null, ['auto_initialize' => false,]);

        $baseForm = $this->factory->create();
        $baseForm->add('foo', CompatibilityHelper::isFormLegacy() ? 'hidden' : 'Symfony\Component\Form\Extension\Core\Type\HiddenType');
        $baseForm->add($form);

        $baseForm->submit([
            'foo' => 'bar',
            $form->getName() => [
                'confirm' => '1',
            ],
        ]);

        $this->assertTrue($form->get('confirm')->isClicked());
        $this->assertEquals(['foo' => 'bar'], $baseForm->getData(), 'data is not mapped parent');
    }
}
