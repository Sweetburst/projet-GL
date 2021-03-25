<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Profil;
use App\Entity\Allergene;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ProfileBackType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('age')
           // ->add('createdAt')
            ->add('user',EntityType::class,[
                'class' => User::Class,
                'choice_label' => 'nom',
            ])
            ->add('allergenes',EntityType::class,[
                'class' => Allergene::Class,
                'choice_label' => 'nom_allergene',
                'multiple' => 'true',
                'expanded' => 'true'
            ])
            ->add('modifier', SubmitType::class )
            ->add('supprimer', SubmitType::class)
            ->add('creeProfile', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Profil::class,
        ]);
    }
}
