<?php

namespace Issei\ConfirmableForm\Tests\Type;

use Issei\ConfirmableForm\Type\OperationType;
use Symfony\Component\Form\Test\TypeTestCase;

class OperationTypeTest extends TypeTestCase
{
    public function testFields()
    {
        $form = $this->factory->create(new OperationType());
        $this->assertCount(3, $form);

        $this->assertEquals('確認', $form->get('confirm')->getConfig()->getOption('label'));
        $this->assertEquals('戻る', $form->get('back')->getConfig()->getOption('label'));
        $this->assertEquals('送信', $form->get('commit')->getConfig()->getOption('label'));

        $view = $form->createView();

        // block prefix at 2nd from last
        $this->assertEquals('confirmable_form_operation', array_slice($view->vars['block_prefixes'], -2, 1)[0]);

        // children also
        foreach (['confirm', 'back', 'commit'] as $name) {
            $this->assertEquals('confirmable_form_operation_' . $name, array_slice($view[$name]->vars['block_prefixes'], -2, 1)[0]);
        }
    }

    public function testSubmit()
    {
        $form = $this->factory->create(new OperationType(), null, ['auto_initialize' => false,]);

        $baseForm = $this->factory->create();
        $baseForm->add('foo', 'hidden');
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
