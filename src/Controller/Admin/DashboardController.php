<?php

namespace App\Controller\Admin;

use App\Entity\Salle;
use App\Entity\Membre;
use App\Entity\Materiel;
use App\Entity\Typemateriel;

use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        $routeBuilder = $this->container->get(AdminUrlGenerator::class);
        $url = $routeBuilder->setController(SalleCrudController::class)->generateUrl();
        return $this->redirect($url);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Sport');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Salles', 'fas fa-list', Salle::class);
        yield MenuItem::linkToCrud('Membre', 'fas fa-list', Membre::class);
        yield MenuItem::linkToCrud('Materiel', 'fas fa-list', Materiel::class);
        yield MenuItem::linkToCrud('Typemateriel', 'fas fa-list', Typemateriel::class);

        
        
    }
}
