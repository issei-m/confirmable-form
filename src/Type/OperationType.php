<?php

namespace Issei\ConfirmableForm\Type;

use Issei\ConfirmableForm\CompatibilityHelper;
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
        $submitType = CompatibilityHelper::isFormLegacy()
            ? 'submit'
            : 'Symfony\Component\Form\Extension\Core\Type\SubmitType'
        ;

        $builder
            ->add('confirm', $submitType, $options['confirm_options'])
            ->add('back',    $submitType, $options['back_options'])
            ->add('commit',  $submitType, $options['commit_options'])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        foreach ($view as $name => $child) {
            // Inserts a block into 2nd from last.
            array_splice($child->vars['block_prefixes'], -1, 0, [$this->getBlockPrefix() . '_' . $name]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'mapped'          => false,
            'confirm_options' => ['label' => 'Confirm'],
            'back_options'    => ['label' => 'Back'],
            'commit_options'  => ['label' => 'Send'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'confirmable_form_operation';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->getBlockPrefix();
    }
}
