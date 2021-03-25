<?php

namespace App\Form;

use App\Entity\Profil;
use App\Entity\Allergene;
use App\Repository\AllergeneRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('age')
            ->add('allergenes2',EntityType::class,[
                'class' => Allergene::class,
                'mapped' => false,
                // 'query_builder' => function (AllergeneRepository $er) {
                //     return $er->createQueryBuilder('u')
                //         ->select('u.nom_allergene')
                //         ->orderBy('u.nom_allergene', 'ASC')
                //         ->distinct();
                // },
                'choice_label' => 'nom_allergene',
                'multiple' => true,
                'expanded' => true 
                
            ])
            //still has user and createdAt
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Profil::class,
        ]);
    }
}
