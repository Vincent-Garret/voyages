<?php

namespace App\Controller\Admin;


use App\Entity\Category;
use App\Entity\Trip;
use App\Repository\CategoryRepository;
use App\Repository\TripRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class TripController extends AbstractController{
    /**
     * @Route("/admin/trip", name="tripList")
     */
    public function trip(TripRepository $tripRepository){
        $trips = $tripRepository->findAll();

        return $this->render('Admin/trip.html.twig', [
            'trips' => $trips
        ]);
    }
}