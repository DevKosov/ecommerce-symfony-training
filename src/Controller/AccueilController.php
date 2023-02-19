<?php
namespace App\Controller;
use App\Repository\LigneCommandeRepository;
use App\Repository\ProductRepository;
use App\Service\DeviseService;
use App\Service\PanierService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
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
    public function navBar(string $supported_locales, string $route, array $params, PanierService $panierService,
                           DeviseService $deviseService)
    {
        $nb_produits = $panierService->getNbProduits();
        return $this->render('navbar.html.twig',[
            "supported_locales"=> $supported_locales,
            "nb_products"=> $nb_produits,
            "route" => $route,
            "params" => $params,
            "currencies"=>$deviseService::CURRENCIES
        ]);
    }

    public function topSales(LigneCommandeRepository $ligneCommandeRepository, ProductRepository $productRepository){

        $mostSoldProducts =  $ligneCommandeRepository->getMostSoldProducts(4);
        $products = [];

        foreach ($mostSoldProducts as $productIndex){
            $products[] = [
                "product"=> $productRepository->findOneBy(['id'=>$productIndex['product_id']]),
                "quantite"=> $productIndex['total_quantity']
            ];
        }


        return $this->render('sidebar.html.twig',[
            "products"=> $products
        ]);
    }

    public function switchCurrency(Request $request, $currency)
    {
        $session = $request->getSession();
        $session->set('chosenCurrency', $currency);

        $referer = $request->headers->get('referer');

        return new RedirectResponse($referer);
    }

}

