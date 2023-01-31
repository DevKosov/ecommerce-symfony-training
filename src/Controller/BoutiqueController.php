<?php
namespace App\Controller;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
class BoutiqueController extends AbstractController
{
    public function boutique(CategorieRepository $categorieRepository)
    {

        $categories = $categorieRepository->findAll();

        return $this->render("boutique/boutique.html.twig",["categories" => $categories]);
    }

    public function productsCategory($idCategory,CategorieRepository $categorieRepository){

        $category = $categorieRepository->findOneBy(['id' => $idCategory]);
        $products = $category->getProducts();

        return $this->render("boutique/products.html.twig",[
            "category" => $category,
            "products" => $products
        ]);
    }
}

