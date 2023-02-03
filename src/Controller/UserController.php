<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[Route('/user')]
class UserController extends AbstractController
{
    public function index(UserRepository $userRepository, SessionInterface $session): Response
    {
        $user = $this->getUser();
        if ($user) {
//            $user = $userRepository->find(['id' => $session->get('userId')]);
            return $this->render('user/index.html.twig');
        }
        else
            return $this->redirectToRoute('accueil');
    }

    #[Route('/{_locale = "%app.supported_locales%"}/signup', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UserRepository $userRepository, SessionInterface $session,UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hashedPassword = $userPasswordHasher->hashPassword($user,$user->getPassword());
            $user->setPassword($hashedPassword);
            $user->setRoles(['ROLE_CLIENT']);
            $userRepository->save($user, true);
            $session->set("userId", $user->getId());
            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/new.html.twig', [
            'user' => $user,
            'form' => $form
        ]);
    }

    public function logout(){
        return $this->redirectToRoute('panier_index');
    }

    public function commandes(SessionInterface $session, UserRepository $userRepository){
        $user = $this->getUser();
        if ($user) {
            $commandes = $user->getCommandes();

            return $this->render("Panier/validation.html.twig", [
                'commandes' => $commandes,
            ]);
        }
        else
            return $this->redirectToRoute('app_user_new');
    }

}
