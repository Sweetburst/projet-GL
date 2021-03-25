<?php

namespace App\Form;

use App\Entity\Allergene;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class AllergeneType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom_allergene')
            ->add('description')
            ->add('createdAt')
            //->add('profils')
            ->add('modifier', SubmitType::class )
            ->add('supprimer', SubmitType::class)
            ->add('creeNewAllergene', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Allergene::class,
        ]);
    }
}
