<?php

namespace App\Form;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use App\Entity\Product;
use App\Entity\ProductionLine;
use App\Entity\Op;
use App\Entity\ProductCategorie;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('reference', TextType::class)
            ->add('imageFile', VichImageType::class, [
                'required' => false,
                'allow_delete' => false,
                'download_uri' => false,
                'image_uri' => false,
                'asset_helper' => false,
            ])
            ->add('designation', TextType::class)
            ->add('productCategorie', EntityType::class, [
                'class' => ProductCategorie::class,
                'choice_label' => 'name',
                'mapped' => true,
                'required' => false,
            ])
            ->add('description', TextType::class)
            ->add('productionLine', EntityType::class, [
                'class' => ProductionLine::class,
                'expanded'  => true,
                'multiple'  => true
            ])
            ->add('price', NumberType::class)
            ->add('amount', NumberType::class)
            ->add('warning_level', NumberType::class)
            ->add('waiting', NumberType::class);

        /**$builder->get('productionLine')->addEventListener(
            FormEvents::PRE_SUBMIT,
            function (FormEvent $event) {
                $form = $event->getForm();
                $form->getParent()->add('ops', EntityType::class, [
                    'class' => Op::class,
                    'expanded'  => true,
                    'multiple'  => true,
                ]);
            }
        );*/
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
