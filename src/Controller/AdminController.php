<?php

namespace App\Controller;

use App\Entity\Membre;
use App\Entity\Produit;
use App\Entity\Commande;
use App\Form\ProduitFormType;
use App\Form\AdminFormMembreType;
use App\Form\AdminFormCommandeType;
use App\Repository\MembreRepository;
use App\Repository\ProduitRepository;
use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AdminController extends AbstractController
{
//---------------------------------------------------------------------------------------------------------------------------------------

    #[Route('/admin', name: 'app_admin')]
    public function adminIndex(): Response
    {
        return $this->render('admin/index.html.twig');
    }
//---------------------------------------------------------------------------------------------------------------------------------------
//---------------------------------------------------------------------------------------------------------------------------------------
#[Route('/admin/produits', name: 'admin_produits')]
#[Route('/admin/produit/new', name: 'admin_new_produit')]
#[Route('/admin/produit/edit/{id}', name: 'admin_edit_produit')]
public function adminFormProduit(ProduitRepository $repo, Request $globals, EntityManagerInterface $manager, Produit $produit = null)
{
    $produits=$repo->findAll();
    if($produit == null) {
        $produit= new Produit;
        $produit->setDateEnregistrement(new \DateTime);
    }

    $form=$this->createForm(ProduitFormType::class, $produit);

    $form->handleRequest($globals);

    if($form->isSubmitted() && $form->isValid()) {
        $manager->persist($produit);
        $manager->flush();
        $this->addFlash('success', "Le produit a bien été édité / enregistré !");

        return $this->redirectToRoute('admin_produits');
    }
    return $this->renderForm('admin/admin_produits.html.twig', [
        'form'=> $form,
        'produits' => $produits,
        'editMode'=> $produit->getId() !== null
    ]);
   
    return ;
}
//---------------------------------------------------------------------------------------------------------------------------------------
#[Route('/admin/produit/delete/{id}', name: 'admin_delete_produit')]
public function adminDeleteProduit(Produit $produit, EntityManagerInterface $manager)
{
    $manager->remove($produit);
    $manager->flush();

    return $this->redirectToRoute('admin_produits');
}
//---------------------------------------------------------------------------------------------------------------------------------------
//---------------------------------------------------------------------------------------------------------------------------------------
#[Route('/admin/membres', name: 'admin_membres')]
#[Route('/admin/membre/new', name: 'admin_new_membre')]
#[Route('/admin/membre/edit/{id}', name: 'admin_edit_membre')]
public function adminFormMembre(MembreRepository $repo, Request $globals, EntityManagerInterface $manager, Membre $membre = null, UserPasswordHasherInterface $hasher)
{
    $membres=$repo->findAll();
    if($membre == null) {
        $membre= new Membre;
        $membre->setDateEnregistrement(new \DateTime);
    }

    $form=$this->createForm(AdminFormMembreType::class, $membre);

    $form->handleRequest($globals);

    if($form->isSubmitted() && $form->isValid()) {

        // si le membre est nouveau et le mdp est vide
        if(!$membre->getId() && $form->get('plainPassword')->getData() == null)
        {
            $this->addFlash('danger', "Un nouveau membre doit avoir un mot de passe.");
            return $this->redirectToRoute('admin_crud_membres_new');
            // je renvoie une erreur
        }

        // si c'est un nouvel utilisateur ou qu'on modifie le mot de passe d'un utilisateur existant
        if ($form->get('plainPassword')->getData()) {
            $membre->setPassword($hasher->hashPassword($membre, $form->get('plainPassword')->getData()));
            // alors on hash le nouveau mdp
        }

        $manager->persist($membre);
        $manager->flush();
        $this->addFlash('success', "Le membre a bien été édité / enregistré !");

        return $this->redirectToRoute('admin_membres');
    }
    return $this->renderForm('admin/admin_membres.html.twig', [
        'form'=> $form,
        'membres' => $membres,
        'editMode'=> $membre->getId() !== null
        ]);
        
    return ;
}
//---------------------------------------------------------------------------------------------------------------------------------------
#[Route('/admin/membre/delete/{id}', name: 'admin_delete_membre')]
public function adminDeleteMembre(Membre $membre, EntityManagerInterface $manager)
{
    $manager->remove($membre);
    $manager->flush();

    return $this->redirectToRoute('admin_membres');
}
//---------------------------------------------------------------------------------------------------------------------------------------
//---------------------------------------------------------------------------------------------------------------------------------------
#[Route("/admin/commandes", name:"admin_commandes")]
// #[Route("/admin/commandes/new", name:"admin_commandes")]
#[Route("/admin/commandes/edit/{id}", name:"admin_edit_commande")]
public function adminFormCommande(CommandeRepository $repo, Commande $commande = null, EntityManagerInterface $manager, Request $request)
{
    // $commandes = $manager->getClassMetadata(Commande::class)->getFieldNames();
    $commandes = $repo->findAll();
    if (!$commande) {
        $commande = new Commande;
        $commande->setDateEnregistrement(new \DateTime());
    }
    $form = $this->createForm(AdminFormCommandeType::class, $commande);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $quantity = $commande->getQuantite();
        $montant = ($commande->getProduit()->getPrix()) * $quantity;

        $commande->setMontant($montant);
        // $commande->setQuantite($quantity);

        $manager->persist($commande);
        $manager->flush();
        $this->addFlash('success', 'La commande a bien été modifiée / créer !');
        return $this->redirectToRoute('admin_commandes');
    }
    return $this->renderForm('admin/admin_commandes.html.twig', [
        'form' => $form,
        'commandes' => $commandes,
        'editMode' => $commande->getId() != NULL
    ]);
}
//---------------------------------------------------------------------------------------------------------------------------------------
#[Route("/admin/commandes/delete/{id}", name:"admin_delete_commande")]
public function adminDeleteCommande(Commande $commande = null, EntityManagerInterface $manager)
{
    if ($commande) {
        $manager->remove($commande);
        $manager->flush();
        $this->addFlash('success', 'La commande a bien été supprimée !');
    }
    return $this->redirectToRoute('admin_commandes');
}
//---------------------------------------------------------------------------------------------------------------------------------------

}
