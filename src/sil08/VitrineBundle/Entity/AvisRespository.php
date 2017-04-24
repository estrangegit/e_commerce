<?php

namespace sil08\VitrineBundle\Entity;

class AvisRespository extends \Doctrine\ORM\EntityRepository
{
    public function getAvisByArticle($article){
        $query = $this->getEntityManager()
            ->createQuery('SELECT a FROM sil08VitrineBundle:Avis a WHERE a.article = :article')
            ->setParameter('article', $article);
        return $query->getResult();
    }
}
