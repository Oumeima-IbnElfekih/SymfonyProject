<?php

namespace App\Controller\Admin;

use App\Entity\Hall;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class HallCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Hall::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
