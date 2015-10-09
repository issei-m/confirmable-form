<?php

namespace Issei\ConfirmableForm\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Issei Murasawa <issei.m7@gmail.com>
 */
class OperationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('confirm', 'submit', ['label' => '確認'])
            ->add('back',    'submit', ['label' => '戻る'])
            ->add('commit',  'submit', ['label' => '送信'])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        foreach ($view as $name => $child) {
            // Inserts a block into 2nd from last.
            array_splice($child->vars['block_prefixes'], -1, 0, [$this->getName() . '_' . $name]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'mapped' => false,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'confirmable_form_operation';
    }
}
