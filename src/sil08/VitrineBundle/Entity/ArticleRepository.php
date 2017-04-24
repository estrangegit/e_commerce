<?php

namespace sil08\VitrineBundle\Entity;

class ArticleRepository extends \Doctrine\ORM\EntityRepository
{
    public function getPlusVendus($nbArticles){

        $em = $this->getEntityManager();
        $query = $em->createQuery('SELECT a as article, SUM(ca.quantite) as nbVentes FROM sil08VitrineBundle:Article a, sil08VitrineBundle:CommandeArticle ca WHERE a = ca.article GROUP BY a ORDER BY nbVentes DESC')
            ->setMaxResults($nbArticles);

        $liste = $query->getResult();

        return $liste;
    }

    public function getArticlesByCategorie($categorie){

        $query = $this->getEntityManager()
                        ->createQuery('SELECT a FROM sil08VitrineBundle:Article a WHERE a.categorie = :categorie ')
                        ->setParameter('categorie', $categorie);
        return $query->getResult();

    }

    public function getSuggestionArticles($article){
        $em = $this->getEntityManager();

        $query = $em->createQuery('SELECT a, SUM(ca2.quantite) as sumQuantite FROM sil08VitrineBundle:Article a, sil08VitrineBundle:CommandeArticle ca1, sil08VitrineBundle:CommandeArticle ca2, sil08VitrineBundle:Commande c 
                                    WHERE ca1.article = :article AND c = ca1.commande AND c = ca2.commande AND a = ca2.article AND a.id NOT IN (:id)
                                     GROUP BY ca2.article ORDER BY sumQuantite DESC')
            ->setParameter('article', $article)
            ->setParameter('id', $article->getId())
            ->setMaxResults(3);

        return $query->getResult();
    }
}
