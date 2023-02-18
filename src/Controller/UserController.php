<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Service\PanierService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Security;

#[Route('/user')]
class UserController extends AbstractController
{
//    #[Route('/{_locale = "%app.supported_locales%"}/compte', name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository, SessionInterface $session): Response
    {
        if ($this->getUser()) {
            return $this->render('user/index.html.twig', [
                'user' => $this->getUser()
            ]);
        }
        else
            return $this->redirectToRoute('accueil');
    }

//    #[Route('/{_locale = "%app.supported_locales%"}/signup', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UserRepository $userRepository, SessionInterface $session, UserPasswordHasherInterface $userPasswordHasher, TokenStorageInterface $tokenStorage): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $hashedPassword = $userPasswordHasher->hashPassword($user,$user->getPassword());
            $user->setPassword($hashedPassword);
            $user->setRoles(["ROLE_CLIENT"]);
            $userRepository->save($user, true);
            $session->set("userId", $user->getId());

            $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
            $tokenStorage->setToken($token);

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/new.html.twig', [
            'user' => $user,
            'form' => $form
        ]);
    }


    public function commandes(PanierService $panierService,UserRepository $userRepository,SessionInterface $session)
    {
        if (!$this->getUser())
            return $this->redirectToRoute('app_user_new');

        $commandes = $this->getUser()->getCommandes()->toArray();

        return $this->render("Panier/validation.html.twig",[
            'commandes' => $commandes,
        ]);
    }
}
