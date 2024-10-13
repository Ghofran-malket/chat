<?php

namespace App\Form;

use App\Entity\Chat;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChatMessageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('receiver', $options['is_hidden'] ? HiddenType::class : TextType::class, [
                'mapped' => true,
                'attr' => $options['is_hidden'] ? ['style' => 'display: none;'] : [],
            ])
            ->add('message', TextType::class, [
                'label' => 'Type your message:',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Chat::class,
            'is_hidden' => false,
        ]);
    }
}
