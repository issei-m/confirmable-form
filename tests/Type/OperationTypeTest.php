<?php

namespace Issei\ConfirmableForm\Tests\Type;

use Issei\ConfirmableForm\Type\OperationType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Test\TypeTestCase;

class OperationTypeTest extends TypeTestCase
{
    /**
     * @var FormInterface
     */
    private $form;

    protected function setUp()
    {
        parent::setUp();

        $this->form = $this->factory->create(new OperationType());
    }

    public function testFields()
    {
        $this->assertCount(3, $this->form);

        $this->assertEquals('確認', $this->form->get('confirm')->getConfig()->getOption('label'));
        $this->assertEquals('戻る', $this->form->get('back')->getConfig()->getOption('label'));
        $this->assertEquals('送信', $this->form->get('commit')->getConfig()->getOption('label'));
    }

    public function testViews()
    {
        $view = $this->form->createView();

        // block prefix at 2nd from last
        foreach (['confirm', 'back', 'commit'] as $name) {
            $this->assertEquals('confirmable_form_operation_' . $name, array_slice($view[$name]->vars['block_prefixes'], -2, 1)[0]);
        }
    }

    public function testSubmit()
    {
        $this->form = $this->factory->create(new OperationType(), null, ['auto_initialize' => false,]);

        $baseForm = $this->factory->create();
        $baseForm->add('foo', 'hidden');
        $baseForm->add($this->form);

        $baseForm->submit([
            'foo' => 'bar',
            $this->form->getName() => [
                'confirm' => '1',
            ],
        ]);

        $this->assertTrue($this->form->get('confirm')->isClicked());
        $this->assertEquals(['foo' => 'bar'], $baseForm->getData(), 'data is not mapped parent');
    }
}
