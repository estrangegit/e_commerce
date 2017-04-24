<?php

namespace sil08\VitrineBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use sil08\VitrineBundle\Entity\Client;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use sil08\VitrineBundle\Entity\Panier;
use sil08\VitrineBundle\Entity\Commande;
use sil08\VitrineBundle\Entity\CommandeArticle;
use sil08\VitrineBundle\Entity\Avis;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class DefaultController extends Controller
{
/*******************************************************************************************************************
*                   ACTIONS PERMETTANT LA GESTION DES INFORMATIONS GENERALES DU SITE
 *******************************************************************************************************************/

    /* Présentation de la page d'accueil */
    public function indexAction()
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
                $client = $this->getUser();

            return $this->render('sil08VitrineBundle:Default:index.html.twig', array('name' => $client->getPrenom()." ".$client->getNom()));

        }else{
            $name = 'visiteur';
            return $this->render('sil08VitrineBundle:Default:index.html.twig', array('name' => $name));

        }
    }

    /*Présentation des informations du client connecté*/
    public function indexLayoutAction(){
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $client = $this->getUser();

            return $this->render('sil08VitrineBundle:Default:indexLayout.html.twig', array('name' => $client->getPrenom()." ".$client->getNom()));

        }else {

            return $this->render('sil08VitrineBundle:Default:indexLayout.html.twig', array('name' => 'visiteur'));

        }
    }

    /*Affichage des mentions légales du site*/
    public function mentionsAction()
    {
        return $this->render('sil08VitrineBundle:Default:mentions.html.twig');
    }

    /*Affichage des articles les plus vendus*/
    public function plusVendusAction( $max = 3 ){

        $em = $this->getDoctrine()->getManager();
        $articlesEtVentes = $em->getRepository('sil08VitrineBundle:Article')
            ->getPlusVendus($max);

        /*
        if(!$articlesEtVentes){
            throw $this->createNotFoundException('Erreur dans le retour des articles les plus vendus');
        }
        */

        return $this->render('sil08VitrineBundle:Default/Article:plusVendus.html.twig', array('articlesEtVentes' => $articlesEtVentes));
    }

/*******************************************************************************************************************
 *                   ACTIONS PERMETTANT LA GESTION DU CLIENT
 *******************************************************************************************************************/

    /*Action permettant l'inscription d'un client*/
    public function inscriptionAction(Request $request)
    {
        $clientNew = new Client();
        $formNew = $this->createForm('sil08\VitrineBundle\Form\ClientType', $clientNew);

        $formNew->handleRequest($request);

        if ($formNew->isSubmitted() && $formNew->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $encoder = $this->container->get('security.password_encoder');
            $encoded = $encoder->encodePassword($clientNew, $clientNew->getMotDePasse());

            $clientNew->setMotDePasse($encoded);
            $clientNew->setAdmin(false);

            $em->persist($clientNew);
            $em->flush();


            $token = new UsernamePasswordToken($clientNew, null, 'main', array('ROLE_USER'));
            $this->get('security.token_storage')->setToken($token);
//            $this->get('session')->set('_security_main',serialize($token));

            return $this->redirectToRoute('sil08_vitrine_homepage');
        }

        return $this->render('@sil08Vitrine/Default/Client/inscription.html.twig', array('formNew' => $formNew->createView()));
    }

    /*Action permettant l'affichage des détails du client*/
    public function showClientAction(Client $client)
    {
        return $this->render('@sil08Vitrine/Default/Client/show.html.twig', array(
            'client' => $client,
        ));
    }

    /*Action permettant la modification des informations du client*/
    public function editClientAction(Request $request, Client $client)
    {
        $editForm = $this->createForm('sil08\VitrineBundle\Form\ClientType', $client);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            $encoder = $this->container->get('security.password_encoder');
            $encoded = $encoder->encodePassword($client, $client->getMotDePasse());

            $client->setMotDePasse($encoded);

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('sil08_client_show', array('id' => $client->getId()));
        }

        return $this->render('@sil08Vitrine/Default/Client/edit.html.twig', array(
            'client' => $client,
            'edit_form' => $editForm->createView(),
        ));
    }

/*******************************************************************************************************************
 *                   ACTIONS PERMETTANT LA GESTION DU CATALOGUE
 *******************************************************************************************************************/

    public function catalogueAction()
    {
        $categories = $this->getDoctrine()->getManager()
                            ->getRepository('sil08VitrineBundle:Categorie')
                            ->findAll();

        if(!$categories){
            throw $this->createNotFoundException('Catégories non trouvées');
        }

        return $this->render('sil08VitrineBundle:Default/Article:catalogue.html.twig', array('categories' => $categories));
    }

    public function articlesParCategorieAction($id){

        $repositoryCateg = $this->getDoctrine()->getManager()->getRepository('sil08VitrineBundle:Categorie');
        $categorie = $repositoryCateg->findOneBy(array('id' => $id));

        $em = $this->getDoctrine()->getManager();

        $articles = $em->getRepository('sil08VitrineBundle:Article')
                        ->getArticlesByCategorie($categorie);

        return $this->render('sil08VitrineBundle:Default/Article:articlesParCategorie.html.twig', array('categorie' => $categorie, 'articles' => $articles));
    }

/*******************************************************************************************************************
 *                   ACTIONS PERMETTANT LA GESTION DU PANIER DE L'UTILISATEUR
 *******************************************************************************************************************/

    public function contenuPanierAction(Request $request){

        $session = $request->getSession();

        $montantPanier = 0;

        if($session->has('panier')) {

            $panier = $session->get('panier');

            $infosPanier = array();

            foreach ($panier->getContenu() as $idArticle => $qteArticle) {

                $repositoryArticle = $this->getDoctrine()->getManager()->getRepository('sil08VitrineBundle:Article');
                $article = $repositoryArticle->findOneBy(array('id' => $idArticle));

                if (!$article) {
                    throw $this->createNotFoundException('Article non trouvée avec id ' . $idArticle);
                }

                $infosLignePanier = array(
                    'idArticle' => $article->getId(),
                    'libelle' => $article->getLibelle(),
                    'prix' => $article->getPrix(),
                    'quantite' => $qteArticle
                );

                $infosPanier[] = $infosLignePanier;
            }

            foreach ($infosPanier as $infoPanier){
                $montantPanier = $montantPanier + $infoPanier['prix'] * $infoPanier['quantite'];
            }
        }
        else{
            $infosPanier = array();
        }

        return $this->render('sil08VitrineBundle:Default/Panier:contenuPanier.html.twig', array('infosPanier' => $infosPanier, 'montantPanier' => $montantPanier));
    }

    public function contenuPanierLayoutAction(Request $request){

        $session = $request->getSession();

        $montantPanier = 0;
        $nbArticles = 0;

        if($session->has('panier')) {

            $panier = $session->get('panier');

            $infosPanier = array();

            foreach ($panier->getContenu() as $idArticle => $qteArticle) {

                $repositoryArticle = $this->getDoctrine()->getManager()->getRepository('sil08VitrineBundle:Article');
                $article = $repositoryArticle->findOneBy(array('id' => $idArticle));

                if (!$article) {
                    throw $this->createNotFoundException('Article non trouvée avec id ' . $idArticle);
                }

                $infosLignePanier = array(
                    'prix' => $article->getPrix(),
                    'quantite' => $qteArticle
                );

                $infosPanier[] = $infosLignePanier;
            }
            foreach ($infosPanier as $infoPanier){
                $montantPanier = $montantPanier + $infoPanier['prix'] * $infoPanier['quantite'];
                $nbArticles = $nbArticles + $infoPanier['quantite'];
            }
        }

        return $this->render('sil08VitrineBundle:Default/Panier:contenuPanierLayout.html.twig', array('montantPanier' => $montantPanier, 'nbArticles' => $nbArticles));
    }


    public function ajoutArticleAction(Request $request, $id, $qte){

        $repositoryArticle = $this->getDoctrine()->getManager()->getRepository('sil08VitrineBundle:Article');
        $article = $repositoryArticle->findOneBy(array('id' => $id));

        if(!$article){
            throw $this->createNotFoundException('Article non trouvée avec id '.$id);
        }

        $session = $request->getSession();

        if($session->has('panier')){
            $panier = $session->get('panier');
        }else{
            $panier = new Panier();
        }

        if($panier->isPresent($article)) {
            $oldQte = $panier->getContenu()[$article->getId()];
            $newQte = $oldQte + $qte;
        }else {
            $newQte = $qte;
        }

        if($newQte <= $article->getStock()){
            $panier->ajoutArticle($article, $qte);
            $session->set('panier', $panier);
        }else{
            $this->addFlash(
                'error',
                'Ajout impossible: seulement ' . $article->getStock() . ' exemplaire(s) de l\'article ' . $article->getLibelle() . ' en stock'
            );
        }

        $listeArticlesTemp = $repositoryArticle->getSuggestionArticles($article);
        $listeArticles = array();

        foreach ($listeArticlesTemp as $listeArticle){
            $listeArticles[] = $listeArticle[0];
        }

        if(count($listeArticles) > 0){
            $response = $this->render('sil08VitrineBundle:Default/Article:suggestionArticles.html.twig', array('listeArticles' => $listeArticles));
        }else{
            $response = $this->redirectToRoute('sil08_vitrine_contenu_panier');
        }

        return $response;
    }

    public function supprimerUnArticleAction(Request $request, $id){

        $session = $request->getSession();

        if($session->has('panier')){
            $panier = $session->get('panier');
        }else{
            $panier = new Panier();
        }

        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('sil08VitrineBundle:Article');

        $article = $repository->findOneBy(array('id'=> $id));

        if(!$article){
            throw $this->createNotFoundException('l\'article avec id: '.$id.' n\'existe pas et ne peux pas être supprimé du panier');
        }

        if($panier->isPresent($article)) {
            $oldQte = $panier->getContenu()[$article->getId()];
            $newQte = $oldQte - 1;
        }else{
            $newQte = -1;
        }

        if($newQte >= 0){
            $panier->supprimerUnArticle($article);
            $session->set('Panier', $panier);
        }

        return $this->redirectToRoute('sil08_vitrine_contenu_panier');
    }

    public function supprimerArticleAction(Request $request, $id){

        $session = $request->getSession();

        if($session->has('panier')){

            $panier = $session->get('panier');

            $em = $this->getDoctrine()->getManager();
            $repository = $em->getRepository('sil08VitrineBundle:Article');

            $article = $repository->findOneBy(array('id'=> $id));

            if(!$article){
                throw $this->createNotFoundException('l\'article avec id: '.$id.' n\'existe pas et ne peux pas être supprimé du panier');
            }

            if($panier->isPresent($article)) {
                $panier->supprimerArticle($article);
            }
        }

        return $this->redirectToRoute('sil08_vitrine_contenu_panier');
    }

    public function viderPanierAction(Request $request){

        $session = $request->getSession();

        if($session->has('panier')) {
            $session->remove('panier');
        }

        $response = $this->forward('sil08VitrineBundle:Default:contenuPanier');

        return $response;
    }

    public function validationPanierAction(Request $request){

        $session =$request->getSession();

        if($session->has('panier') &&
            (count($session->get('panier')->getContenu()) > 0) &&
                $this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')){

            $panier = $session->get('panier');
            $client = $this->getUser();

            $em = $this->getDoctrine()->getManager();

            $commandePossible = true;

            foreach ($panier->getContenu() as $idArticle => $qteArticle){

                $repositoryArticle = $em->getRepository('sil08VitrineBundle:Article');

                $article = $repositoryArticle->findOneBy(array('id' => $idArticle));

                if (!$article) {
                    throw $this->createNotFoundException('Article non trouvée avec id ' . $idArticle);
                }

                if($article->getStock() < $qteArticle){
                    $commandePossible = false;
                    $session->getFlashBag()->add('error',
                        "Commande impossible: l'article ".$article->getLibelle()." a ".$article->getStock()." exemplaire(s) en stock");
                }
            }

            if(count($panier->getContenu()) == 0){
                $commandePossible == false;

                $session->getFlashBag()->add('error',
                    "Commande impossible: aucun article dans le panier");
            }

            if($commandePossible) {

                $commande = new Commande();
                $commande->setClient($client);
                $date = new \DateTime();
                $date->setTimezone(new \DateTimeZone('Europe/Paris'));
                $commande->setDate($date);
                $commande->setEtat('En préparation');

                $em->persist($commande);
                $em->flush();

                $infosCommande = array();

                $montantCommande = 0;
                $nbArticles = 0;

                foreach ($panier->getContenu() as $idArticle => $qteArticle) {

                    $repositoryArticle = $em->getRepository('sil08VitrineBundle:Article');

                    $article = $repositoryArticle->findOneBy(array('id' => $idArticle));

                    if (!$article) {
                        throw $this->createNotFoundException('Article non trouvée avec id ' . $idArticle);
                    }

                    $article->setStock($article->getStock() - $qteArticle);

                    $commandeArticle = new CommandeArticle();
                    $commandeArticle->setArticle($article);
                    $commandeArticle->setCommande($commande);
                    $commandeArticle->setPrix($article->getPrix());
                    $commandeArticle->setQuantite($qteArticle);


                    $infosLigneCommande = array(
                        'idArticle' => $article->getId(),
                        'libelle' => $article->getLibelle(),
                        'prix' => $article->getPrix(),
                        'quantite' => $qteArticle
                    );

                    $infosCommande[] = $infosLigneCommande;

                    $em->persist($commandeArticle);
                    $em->flush();
                }

                foreach ($infosCommande as $infoCommande) {
                    $montantCommande = $montantCommande + $infoCommande['prix'] * $infoCommande['quantite'];
                    $nbArticles = $nbArticles + $infoCommande['quantite'];
                }

                $session->remove('panier');

                return $this->render('sil08VitrineBundle:Default/Panier:validationPanier.html.twig', array('commande' => $commande,
                    'infosCommande' => $infosCommande,
                    "nbArticles" => $nbArticles,
                    "montantCommande" => $montantCommande));
            }else{
                return $this->redirectToRoute('sil08_vitrine_contenu_panier');
            }

        }else{
            if($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')){

                $session->getFlashBag()->add('error', "Validation impossible : il n'y a aucun article dans votre panier");

                return $this->redirectToRoute('sil08_vitrine_contenu_panier');
            }else{
                return $this->redirectToRoute('login');
            }
        }
    }

/*******************************************************************************************************************
 *                   ACTIONS PERMETTANT LA GESTION DES COMMANDES
 *******************************************************************************************************************/

public function indexCommandeAction(Request $request)
    {
        $session = $request->getSession();

        if($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {

            $em = $this->getDoctrine()->getManager();
            $client = $this->getUser();

            $commandes = $em->getRepository('sil08VitrineBundle:Commande')
                            ->getCommandesByClient($client);

            return $this->render('sil08VitrineBundle:Default/Commande:index.html.twig', array(
                'commandes' => $commandes,
            ));
        }else{
            return $this->redirectToRoute('login');
        }
    }

    public function showCommandeAction(Commande $commande)
    {
        $em = $this->getDoctrine()->getManager();

        $commandesArticles = $em->getRepository('sil08VitrineBundle:CommandeArticle')
                                ->getCommandesArticlesByCommande($commande);

        $articles = array();

        $montantCommande = 0;

        foreach ($commandesArticles as $commandeArticle){

            $infosArticle = array(
                'libelle' => $commandeArticle->getArticle()->getLibelle(),
                'prix' => $commandeArticle->getPrix(),
                'quantite' => $commandeArticle->getQuantite()
            );

            $montantCommande = $montantCommande + ($commandeArticle->getPrix()) * $commandeArticle->getQuantite();

            $articles[] = $infosArticle;
        }

        $infosCommande = array(
            'idCommande' => $commande->getId(),
            'montantCommande' => $montantCommande,
            'date'=> $commande->getDate(),
            'etat' => $commande->getEtat(),
            'articles' => $articles
        );

        return $this->render('sil08VitrineBundle:Default/Commande:show.html.twig', array(
            'infosCommande' => $infosCommande,
        ));
    }

/*******************************************************************************************************************
 *                   ACTIONS PERMETTANT LA GESTION DES AVIS LAISSES POUR UN ARTICLE
 *******************************************************************************************************************/

    public function showAndEditAvisArticleAction(Request $request, $id){

        $client = $this->getUser();

        $em = $this->getDoctrine()->getManager();
        $repositoryArticle = $em->getRepository('sil08VitrineBundle:Article');
        $article = $repositoryArticle->findOneBy(array('id' => $id));

        if (!$article) {
            throw $this->createNotFoundException('Article non trouvée avec id ' . $id);
        }

        $listeAvis = $em->getRepository('sil08VitrineBundle:Avis')
                    ->getAvisByArticle($article);

        $avis = new Avis();

        $form = $this->createFormBuilder($avis)
                    ->add('note', ChoiceType::class, array(
                        'choices'  => array(
                            '5' => 5,
                            '4' => 4,
                            '3' => 3,
                            '2' => 2,
                            '1' => 1)))
                    ->add('commentaire', 'textarea')
                    ->getForm();

        if ($request->isMethod('POST')) {

            $form->handleRequest($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $avis->setArticle($article);
                $avis->setClient($client);
                $em->persist($avis);
                $em->flush();

                $this->addFlash(
                    'success',
                    'Votre commentaire a bien été pris en considération'
                );

                return $this->redirectToRoute('sil08_avis_article', array('id' => $article->getId()));
            }
        }

        return $this->render('sil08VitrineBundle:Default/Article:avis.html.twig', array(
            'article' => $article,
            'listeAvis' => $listeAvis,
            'form' => $form->createView(),
        ));
    }
}
