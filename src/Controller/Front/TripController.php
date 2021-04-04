<?php


namespace App\Controller\Front;

use App\Entity\User;
use App\Form\RegistrationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CategoryRepository;
use App\Repository\TripRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Form\TripType;
use App\Entity\Trip;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class TripController extends AbstractController
{
    /**
     * @Route("/user/insert/trip", name="insert")
     */
    public function insertTrip(
        Request $request,
        EntityManagerInterface $entityManager,
        SluggerInterface $slugger
    ){
        $trip = new Trip();
        $tripForm = $this->createForm(TripType::class, $trip);
        $tripForm->handleRequest($request);

        if ($tripForm->isSubmitted() && $tripForm->isValid()){
            $image = $tripForm->get('image')->getData();
            if($image){
                $originalFileName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFileName = $slugger->slug($originalFileName);
                $newFileName = $safeFileName.'-'.uniqid().'.'.$image->guessExtension();
                $image->move(
                    $this->getParameter('img'),
                    $newFileName
                );
                
                $trip->setImage($newFileName);
                $entityManager->persist($trip);
                $entityManager->flush();
            }
            $entityManager->persist($trip);
            $entityManager->flush();
            $this->addFlash('Success', 'Votre voyage a été crée !');
            return $this->redirectToRoute('tripList');
        }
        return $this->render('Front/tripInsert.html.twig', [
            'tripForm' => $tripForm->createView(),
        ]);
    }
}