<?php

namespace App\Controller;

use App\Repository\ProduitRepository;
use App\Repository\CommandeRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AppController extends AbstractController
{
    #[Route('/', name: 'app_app')]
    public function index(ProduitRepository $repo): Response
    {
        return $this->render('app/index.html.twig', [
            'produits' => $repo->findAll()
        ]);
    }
//---------------------------------------------------------------------------------------------------------------------------------------
    #[Route("/profil", name:"app_profil")]
    public function profil(CommandeRepository $repo)
    {
        $commandes = $repo->findBy(['membre' => $this->getUser()]);

        return $this->render("app/show_profil.html.twig", [
            'commandes' => $commandes
        ]);
    }
//---------------------------------------------------------------------------------------------------------------------------------------
    #[Route("/produit/{id}", name:"app_produit")]
    public function showProduit($id, ProduitRepository $repo)
    {
        $produit = $repo->find($id);

        return $this->render("app/show_produit.html.twig", [
            'produit' => $produit
        ]);
    }
//---------------------------------------------------------------------------------------------------------------------------------------
#[Route("/commande/{id}", name:"app_commande")]
public function showCommande($id, CommandeRepository $repo)
{
    $commande = $repo->find($id);

    return $this->render("app/show_commande.html.twig", [
        'commande' => $commande
    ]);
}
//---------------------------------------------------------------------------------------------------------------------------------------

}
