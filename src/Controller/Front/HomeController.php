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
    public function trips(
        TripRepository $tripRepository,
        CategoryRepository $categoryRepository)
    {
        $categories = $categoryRepository->findAll();
        $trips = $tripRepository->findAll();
        return $this->render('Front/home.html.twig', [
            'categories' => $categories,
            'trips' => $trips
        ]);
    }
}