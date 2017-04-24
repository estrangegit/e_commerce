<?php

namespace sil08\VitrineBundle\Entity;


class CommandeRepository extends \Doctrine\ORM\EntityRepository
{
    public function getCommandesByClient($client){
        $query = $this->getEntityManager()
            ->createQuery('SELECT c FROM sil08VitrineBundle:Commande c WHERE c.client = :client')
            ->setParameter('client', $client);

        return $query->getResult();
    }
}
