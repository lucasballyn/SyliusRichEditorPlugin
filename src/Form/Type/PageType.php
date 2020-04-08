<?php

namespace MonsieurBiz\SyliusRichEditorPlugin\Form\Type;

use MonsieurBiz\SyliusRichEditorPlugin\Entity\Page;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titel'
            ])
            ->add('parent', EntityType::class, [
                'required' => false,
                'placeholder' => '/',
                'class' => Page::class,
                'choice_label' => 'slugDisplay',
                'empty_data' => null
            ])
            ->add('blocks', RichEditorType::class, [
                'label' => 'monsieurbiz_richeditor_plugin.ui.blocks',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Page::class,
        ]);
    }
}
