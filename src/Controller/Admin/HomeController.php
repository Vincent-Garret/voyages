<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CategoryRepository;
use App\Repository\TripRepository;


class HomeController extends AbstractController {

    /**
    * @Route ("/admin", name="HomeAdmin");
    */
    public function home()
    {
        return $this->render('Admin/home.html.twig');
    }
}