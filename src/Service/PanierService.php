<?php
namespace App\Service;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
// Service pour manipuler le panier et le stocker en session
class PanierService {

    const PANIER_SESSION = "panier";    // Le nom de la variable de session du panier
    private $session;                   // Le service Session
    private $productRepository;
    private $panier;                    // Tableau associatif idProduit => valeur ['produit','quantite']

    // constructeur du service
    public function __construct(SessionInterface $session, ProductRepository $productRepository) {
        $this->session = $session;
        $this->productRepository = $productRepository;

        if ($this->session->has(PanierService::PANIER_SESSION))
            $this->panier = $this->session->get(PanierService::PANIER_SESSION);
        else {
            $this->panier = [];
            $this->panier = $this->session->set(PanierService::PANIER_SESSION,$this->panier);
        }
    }

    // getContenu renvoie le contenu du panier
    // Tableau d'éléments [ "produit" => un produit, "quantite" => quantite ]
    public function getContenu() {
        return $this->panier;
    }

    // getTotal renvoie le montant total du panier
    public function getTotal() {
        if (empty($this->panier))
            return 0;
        else{
            $total = 0;
            foreach ($this->panier as $produit){
                dump($produit);
                $total += $produit['produit'][0]->getPrix() * $produit['quantite'];
            }
            return $total;
        }
    }

    // getNbProduits renvoie le nombre de produits dans le panier
    public function getNbProduits() {
        if (empty($this->panier))
            return 0;
        else{
            $total = 0;
            foreach ($this->panier as $produit){
                $total += $produit['quantite'];
            }
            return $total;
        }
    }

    // ajouterProduit ajoute au panier le produit $idProduit en quantite $quantite
    public function ajouterProduit(int $idProduit, int $quantite = 1) {

        if (isset($this->panier[$idProduit]))
            $this->panier[$idProduit]['quantite'] += $quantite;
        else {
            $produit = $this->productRepository->findBy(['id'=> $idProduit]);
            $this->panier[$idProduit] = [
                'produit' => $produit,
                'quantite' => $quantite
            ];
        }

        $this->session->set(PanierService::PANIER_SESSION,$this->panier);
    }

    // enleverProduit enlève du panier le produit $idProduit en quantite $quantite
    public function enleverProduit(int $idProduit, int $quantite = 1) {

        if (isset($this->panier[$idProduit])) {

            if ($quantite >= $this->panier[$idProduit]['quantite'])
                $this->supprimerProduit($idProduit);
            else
                $this->panier[$idProduit]['quantite'] -= $quantite;

            $this->session->set(PanierService::PANIER_SESSION,$this->panier);
        }
    }

    // supprimerProduit supprime complètement le produit $idProduit du panier
    public function supprimerProduit(int $idProduit) {
        if (isset($this->panier[$idProduit])) {
            unset($this->panier[$idProduit]);
            $this->session->set(PanierService::PANIER_SESSION, $this->panier);
        }
    }

    // vider vide complètement le panier
    public function vider()
    {
        $this->panier = [];
        $this->session->set(PanierService::PANIER_SESSION,$this->panier);
    }
}