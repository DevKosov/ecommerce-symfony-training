<?php
namespace App\Controller;
use App\Repository\ProductRepository;
use App\Service\PanierService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class AccueilController extends AbstractController
{
    public function accueil()
    {
        return $this->render("accueil.html.twig");
    }
    public function contact()
    {
        return $this->render("contact.html.twig");
    }
    public function searchProducts($searchItem,ProductRepository $productRepository){

        $products = $productRepository->like($searchItem);

        return $this->render("boutique/search.html.twig",[
            "searchItem" => $searchItem,
            "products" => $products
        ]);
    }
    public function navBar(string $supported_locales, string $route, array $params, PanierService $panierService, SessionInterface $session)
    {
        $nb_produits = $panierService->getNbProduits();
        return $this->render('navbar.html.twig',[
            "supported_locales"=> $supported_locales,
            "nb_products"=> $nb_produits,
            "route" => $route,
            "params" => $params
        ]);
    }
}

