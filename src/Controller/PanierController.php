<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Service\PanierService;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class PanierController extends AbstractController
{
    public function index(PanierService $panierService)
    {
        // Recuperer les informations nessecaires pour la vue de panier
        $panier = $panierService->getContenu();
        $nb_items = $panierService->getNbProduits();
        $prix_total = $panierService->getTotal();

        dump($panier);
        return $this->render("Panier/index.html.twig",[
            'panier' => $panier,
            'nb_products' => $nb_items,
            'prix_total' => $prix_total
            ]);

    }

    public function ajouter(int $idProduit, int $quantite,PanierService $panierService)
    {
        $panierService->ajouterProduit($idProduit,$quantite);
        return $this->redirectToRoute('panier_index');
    }

    public function enlever($idProduit, $quantite, PanierService $panierService)
    {
        $panierService->enleverProduit($idProduit,$quantite);
        return $this->redirectToRoute('panier_index');
    }

    public function supprimer($idProduit, PanierService $panierService)
    {
        $panierService->supprimerProduit($idProduit);
        return $this->redirectToRoute('panier_index');
    }

    public function validation(PanierService $panierService,UserRepository $userRepository,SessionInterface $session,
                               EntityManagerInterface $entityManager, MailerInterface $mailer)
    {
        if (!$this->getUser())
            return $this->redirectToRoute('app_user_new');

        if (empty($panierService->getContenu()))
            return $this->redirectToRoute('commandes');

        $panierService->panierToCommande($this->getUser(),$entityManager);
        $commandes = $this->getUser()->getCommandes()->toArray();

        $user = $userRepository->findOneBy(['email'=>$this->getUser()->getUserIdentifier()]);

        $lastCommade = $commandes[array_key_last($commandes)];

        dump($lastCommade);

        $commandeId = $lastCommade->getId();
        $email = (new Email())
            ->from('noreply@alrrahmani.fr')
            ->to($user->getEmail())
            ->subject("Validation de votre Commande!")
            ->text("Votre commande numero $commandeId est en cours de peraration! \n Merci de votre commande");
        $mailer->send($email);

        return $this->redirectToRoute("commandes");
    }

    public function vider(PanierService $panierService)
    {
        $panierService->vider();
        return $this->redirectToRoute('panier_index');
    }

}