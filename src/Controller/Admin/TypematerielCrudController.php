<?php

namespace App\Controller\Admin;

use App\Entity\Typemateriel;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;

class TypematerielCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Typemateriel::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('nom'),
            TextEditorField::new('description')
        ];
    }
    
}
