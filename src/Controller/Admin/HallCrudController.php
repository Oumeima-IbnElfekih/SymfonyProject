<?php

namespace App\Controller\Admin;

use App\Entity\Hall;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use Doctrine\ORM\QueryBuilder;

class HallCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
    return Hall::class;
    }

    public function configureFields(string $pageName): iterable
    {

    return [
        IdField::new('id')->hideOnForm(),
        AssociationField::new('membre'),
        BooleanField::new('publie')
        ->onlyOnForms()
       // ->hideWhenCreating()
       ,
        TextField::new('description'),

        AssociationField::new('materiels')
        ->onlyOnForms()
        // on ne souhaite pas gérer l'association entre les
        // [objets] et la [galerie] dès la crétion de la
        // [galerie]
        ->hideWhenCreating()
        ->setTemplatePath('admin/fields/salle_materiel.html.twig')
        // Ajout possible seulement pour des [objets] qui
        // appartiennent même propriétaire de l'[inventaire]
        // que le [createur] de la [galerie]
        ->setQueryBuilder(
            function (QueryBuilder $queryBuilder) {
            // récupération de l'instance courante de [galerie]
            $currentHall = $this->getContext()->getEntity()->getInstance();
            $membre = $currentHall->getMembre();
            $memberId = $membre->getId();
            // charge les seuls [objets] dont le 'owner' de l'[inventaire] est le [createur] de la galerie
            $queryBuilder->leftJoin('entity.salle', 'i')
                ->leftJoin('i.proprietaire', 'm')
                ->andWhere('m.id = :member_id')
                ->setParameter('member_id', $memberId);    
            return $queryBuilder;
            }
           ),
    ];

   
}}
