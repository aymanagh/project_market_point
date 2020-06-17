<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Op;
use App\Entity\ProductCategorie;
use App\Entity\ProductSearch;

class ProductSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('reference', SearchType::class, [
                'required' => false,
                'label' => false,
                'attr' => [
                    'placeholder' => 'Référence MABEC',
                ]
            ])
            ->add('productCategorie', EntityType::class, [
                'class' => ProductCategorie::class,
                'choice_label' => 'name',
                'label' => false,
                'mapped' => true,
                'required' => false,
            ]);
            /*->add('liness', EntityType::class, [
                'class' => Line::class,
                'choice_label' => 'name',
                'label' => false,
                'mapped' => false,
                'required' => false,
            ]);*/
        /*->add('ops', EntityType::class, [
                'class' => Op::class,
                'choice_label' => 'name',
                'label' => false,
                'mapped' => false,
                'required' => false,
            ])*/
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ProductSearch::class,
            'method' => 'get',
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}
