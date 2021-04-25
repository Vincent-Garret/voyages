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
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

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
        $trips = $tripRepository->findBy([], ['id' => 'DESC'], 8, 0);
        return $this->render('Front/home.html.twig', [
            'categories' => $categories,
            'trips' => $trips
        ]);
    }

    /**
     * @Route("/register", name="register")
     */
    public function register(
        Request $request,
        EntityManagerInterface $entityManager,
        SluggerInterface $slugger,
        UserRepository $userRepository,
        UserPasswordEncoderInterface $passwordEncoder
    )
    {
        $user = new User();

        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);

        if( $form->isSubmitted() && $form->isValid()){
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('password')->getData()
                )
            );
            $user->setRoles(["ROLE_USER"]);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('trips');
        }

        return $this->render('Front/register.html.twig', [
            'registrationForm' => $form->createView()
        ]);

    }
}