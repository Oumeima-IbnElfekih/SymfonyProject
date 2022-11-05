<?php

namespace App\Form;

use App\Entity\Hall;
use App\Repository\MaterielRepository;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class HallType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $hall = $options['data'] ?? null;
        $membre = $hall->getMembre();
        $builder
            ->add('description')
            ->add('publie')
            
            ->add('materiels', null, [
                'query_builder' => function (MaterielRepository $er) use ($membre) {
                        return $er->createQueryBuilder('g')
                        ->leftJoin('g.salle', 'i')
                        ->andWhere('i.proprietaire = :membre')
                        ->setParameter('membre', $membre)
                        ;
                    }
                ])
             ->add('membre', null, [
                'disabled'   => true,
            ])
            
        ;
           //dump($options);
          // var_dump($options);
   
          
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Hall::class,
        ]);
    }
}
