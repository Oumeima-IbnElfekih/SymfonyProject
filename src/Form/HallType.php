<?php

namespace App\Form;

use App\Entity\Hall;
use App\Entity\Membre;
use App\Repository\MaterielRepository;
use App\Repository\MembreRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Security\Core\Security ;
class HallType extends AbstractType
{   private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $hall = $options['data'] ?? null;
       // $membre = $hall->getMembre();
        $membre = $this->security->getUser()->membre;
      //  var_dump($membre);
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
             ->add('membre',null,[
              
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
