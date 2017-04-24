<?php

namespace sil08\VitrineBundle\Entity;

class CommandeArticleRespository extends \Doctrine\ORM\EntityRepository
{
    public function getCommandesArticlesByCommande($commande){
        $query = $this->getEntityManager()
            ->createQuery('SELECT ca FROM sil08VitrineBundle:CommandeArticle ca WHERE ca.commande = :commande')
            ->setParameter('commande', $commande);
        return $query->getResult();
    }
}
