<?php

namespace Issei\ConfirmableForm\Type;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Issei Murasawa <issei.m7@gmail.com>
 */
class FormTypeOperationExtension extends AbstractTypeExtension
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (!$options['confirmable']) {
            return;
        }

        $builder
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
                $form = $event->getForm();

                if ($form->isRoot() && $form->getConfig()->getOption('compound')) {
                    $form->add('_operation', new OperationType(), ['auto_initialize' => false]);
                }
            })
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        if ($options['confirmable'] && isset($view['_operation'])) {
            $operationView = $view['_operation'];

            if ($form->isValid() && $form->get('_operation')->get('confirm')->isClicked()) {
                unset($operationView['confirm']);
                $view->vars['position'] = 'confirm';
            } else {
                unset($operationView['back'], $operationView['commit']);
                $view->vars['position'] = 'input';
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'confirmable' => false,
            ])
            ->setAllowedTypes('confirmable', 'bool')
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return 'form';
    }
}
