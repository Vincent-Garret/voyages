<?php


namespace App\Controller\Front;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CategoryRepository;
use App\Repository\TripRepository;

class HomeController extends AbstractController
{
    /**
     * @Route("", name="home")
     */
    public function home()
    {
        return $this->render('Front/home.html.twig');
    }

    public function trips(TripRepository $tripRepository)
    {
        $trips = $tripRepository->findAll();
        return $this->render('Front/home.html.twig', [
            'trips' => $trips
        ]);
    }
}