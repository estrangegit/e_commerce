<?php
namespace sil08\VitrineBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use sil08\VitrineBundle\Entity\Categorie;
use sil08\VitrineBundle\Entity\Article;
use sil08\VitrineBundle\Entity\Commande;
use Symfony\Component\HttpFoundation\File\File;

class AdministrationController extends Controller
{
/*********************************************************************************************
 *              ACCUEIL DE L'ADMINISTRATION
 **********************************************************************************************/

    public function acceuilAction(){
        return $this->render('sil08VitrineBundle:Administration:administrationAccueil.html.twig');
    }

    /*Présentation des informations de l'utilisateur connecté*/
    public function infosUtilisateurLayoutAction(){
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $utilisateur = $this->getUser();

            return $this->render('sil08VitrineBundle:Administration:infosUtilisateurLayout.html.twig',
                array('name' => $utilisateur->getPrenom()." ".$utilisateur->getNom()));

        }else {
            return $this->render('sil08VitrineBundle:Administration:infosUtilisateurLayout.html.twig',
                array('name' => 'visiteur'));
        }
    }

/*********************************************************************************************
 *              ACTIONS CONCERNANT LA GESTION DES CATEGORIES
 **********************************************************************************************/

    /*Liste toutes les catégories*/
    public function indexCategorieAction()
    {
        $em = $this->getDoctrine()->getManager();

        $categories = $em->getRepository('sil08VitrineBundle:Categorie')->findAll();

        return $this->render('@sil08Vitrine/Administration/Categorie/index.html.twig', array(
            'categories' => $categories,
        ));
    }

    /*Permet de créer une nouvelle catégorie*/
    public function newCategorieAction(Request $request)
    {
        $categorie = new Categorie();
        $form = $this->createForm('sil08\VitrineBundle\Form\CategorieType', $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($categorie);
            $em->flush();

            return $this->redirectToRoute('administration_categorie_index');
        }

        return $this->render('@sil08Vitrine/Administration/Categorie/new.html.twig', array(
            'categorie' => $categorie,
            'form' => $form->createView(),
        ));
    }


    /*Permet la modification d'une catégorie existante*/
    public function editCategorieAction(Request $request, Categorie $categorie)
    {
        $editForm = $this->createForm('sil08\VitrineBundle\Form\CategorieType', $categorie);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('administration_categorie_index', array('id' => $categorie->getId()));
        }

        return $this->render('@sil08Vitrine/Administration/Categorie/edit.html.twig', array(
            'categorie' => $categorie,
            'edit_form' => $editForm->createView(),
        ));
    }

/*********************************************************************************************
 *              ACTIONS CONCERNANT LA GESTION DES CLIENTS
 **********************************************************************************************/

    public function indexClientAction()
    {
        $em = $this->getDoctrine()->getManager();

        $clients = $em->getRepository('sil08VitrineBundle:Client')->findAll();

        return $this->render('@sil08Vitrine/Administration/Client/index.html.twig', array(
            'clients' => $clients,
        ));
    }

    public function editRoleClientAction($id, $admin){

        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            $utilisateurConnecte = $this->getUser();

            if(($utilisateurConnecte->getId() != $id)){

                $em = $this->getDoctrine()->getManager();

                $repositoryClient = $em->getRepository('sil08VitrineBundle:Client');
                $utilisateur = $repositoryClient->findOneBy(array('id' => $id));

                if (!$utilisateur) {
                    throw $this->createNotFoundException('Utilisateur non trouvée avec id ' . $id);
                }

                if($admin){
                    $utilisateur->setAdmin(true);
                }else{
                    $utilisateur->setAdmin(false);
                }
                $em->flush();
            }else{
                throw new \Exception('L\'utilisateur connecté ne peut pas modifier ses droits');
            }

        }else{
            throw $this->createAccessDeniedException();
        }

        return $this->redirectToRoute('administration_client_index');
    }

/*********************************************************************************************
 *              ACTIONS CONCERNANT LA GESTION DES ARTICLES
 **********************************************************************************************/

    /*Présente le choix des catégories pour lister les articles*/
    public function indexArticleAction()
    {
        $em = $this->getDoctrine()->getManager();

        $categories = $em->getRepository('sil08VitrineBundle:Categorie')->findAll();

        return $this->render('@sil08Vitrine/Administration/Article/index.html.twig', array(
            'categories' => $categories,
        ));
    }

    /*Présente les articles listés par catégorie*/
    public function articlesParCategorieAction($id)
    {

        $repositoryCateg = $this->getDoctrine()->getManager()->getRepository('sil08VitrineBundle:Categorie');
        $categorie = $repositoryCateg->findOneBy(array('id' => $id));

        $em = $this->getDoctrine()->getManager();

        $articles = $em->getRepository('sil08VitrineBundle:Article')
                        ->getArticlesByCategorie($categorie);

        return $this->render('sil08VitrineBundle:Administration/Article:articlesParCategorie.html.twig', array('categorie' => $categorie, 'articles' => $articles));
    }

    /*Permet la création de nouveaux articles*/
    public function newArticleAction(Request $request, $idCateg)
    {
        $repositoryCateg = $this->getDoctrine()->getManager()->getRepository('sil08VitrineBundle:Categorie');
        $categorie = $repositoryCateg->findOneBy(array('id' => $idCateg));

        $article = new Article();
        $form = $this->createForm('sil08\VitrineBundle\Form\ArticleType', $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $file = $article->getImage();
            $fileName = $this->get('app.image_uploader')->upload($file);

            $article->setImage($fileName);

            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();

            return $this->redirectToRoute('administration_article_index');
        }

        return $this->render('@sil08Vitrine/Administration/Article/new.html.twig', array(
            'article' => $article,
            'form' => $form->createView(),
            'categorie' => $categorie
        ));
    }

    /*Permet la modification des informations concernant les articles*/
    public function editArticleAction(Request $request, Article $article, $idCateg)
    {
        $repositoryCateg = $this->getDoctrine()->getManager()->getRepository('sil08VitrineBundle:Categorie');
        $categorie = $repositoryCateg->findOneBy(array('id' => $idCateg));

        $article->setImage(
            new File($this->getParameter('images_directory').'/'.$article->getImage())
        );

        $editForm = $this->createForm('sil08\VitrineBundle\Form\ArticleType', $article);

        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            $file = $article->getImage();
            $fileName = $this->get('app.image_uploader')->upload($file);

            $article->setImage($fileName);

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('administration_articles_par_categorie', array('id' => $idCateg));
        }

        return $this->render('@sil08Vitrine/Administration/Article/edit.html.twig', array(
            'article' => $article,
            'edit_form' => $editForm->createView(),
            'categorie' => $categorie
        ));
    }

/*********************************************************************************************
 *              ACTIONS CONCERNANT LA GESTION DES COMMANDES
 **********************************************************************************************/

    /*Présente le choix des clients pour lister les commandes*/
    public function indexCommandeAction(){
        $em = $this->getDoctrine()->getManager();

        $clients = $em->getRepository('sil08VitrineBundle:Client')->findAll();

        return $this->render('@sil08Vitrine/Administration/Commande/index.html.twig', array(
            'clients' => $clients,
        ));
    }

    /*Présente les commande listées par client*/
    public function commandesParClientAction($idClient){

        $repositoryClient = $this->getDoctrine()->getManager()->getRepository('sil08VitrineBundle:Client');
        $client = $repositoryClient->findOneBy(array('id' => $idClient));

        $em = $this->getDoctrine()->getManager();

        $commandes = $em->getRepository('sil08VitrineBundle:Commande')
                        ->getCommandesByClient($client);

        return $this->render('@sil08Vitrine/Administration/Commande/commandesParClient.html.twig', array('client' => $client, 'commandes' => $commandes));
    }

    /* Action permettant la modification de l'état de la commande*/
    public function editCommandeAction(Request $request, Commande $commande, $idClient)
    {
        $repositoryClient = $this->getDoctrine()->getManager()->getRepository('sil08VitrineBundle:Client');
        $client = $repositoryClient->findOneBy(array('id' => $idClient));

        if (!$client) {
            throw $this->createNotFoundException('Utilisateur non trouvée avec id ' . $idClient);
        }


        /*Récupération des informations qui composent la commande*/
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

        $editForm = $this->createForm('sil08\VitrineBundle\Form\CommandeType', $commande);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('administration_commande_par_client', array('idClient' => $idClient));
        }

        return $this->render('@sil08Vitrine/Administration/Commande/edit.html.twig', array(
            'commande' => $commande,
            'montantCommande' => $montantCommande,
            'articles' => $articles,
            'edit_form' => $editForm->createView(),
            'client' => $client
        ));
    }


}