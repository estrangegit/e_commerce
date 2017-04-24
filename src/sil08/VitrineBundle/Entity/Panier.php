<?php
/**
 * Created by IntelliJ IDEA.
 * User: estrangine
 * Date: 22/11/2016
 * Time: 08:53
 */

namespace sil08\VitrineBundle\Entity;


class Panier
{
    private $contenu;

    //Tableau - contenu[i] = quantite d'article d’id=i dans le panier)

    public function __construct()
    {
        $this->contenu = array();
    }

    public function getContenu()
    {
        return $this->contenu;
    }

    public function setContenu($contenu){
        $this->contenu = $contenu;
    }

    public function ajoutArticle(Article $article, $qte = 1)
    {
        $present = $this->isPresent($article);

        if (!$present) {
            $this->contenu[$article->getId()] = $qte;
        }else{
            $this->contenu[$article->getId()] = $this->contenu[$article->getId()] + $qte;
        }
    }

    public function supprimerUnArticle ($article) {

        $present = $this->isPresent($article);

        if($present && $this->contenu[$article->getId()] > 0){
            $this->contenu[$article->getId()] = $this->contenu[$article->getId()] - 1;
        }

        if($present && $this->contenu[$article->getId()] == 0){
            $this->supprimerArticle($article);
        }
    }

    public function supprimerArticle($article)
    {
        $present = $this->isPresent($article);

        if ($present) {
            unset($this->contenu[$article->getId()]);
        }

    }

    public function isPresent($article)
    {
        $present = false;

        foreach ($this->contenu as $artId => $quantite){
            if($artId == $article->getId()){
                $present = true;
                break;
            }
        }
        return $present;
    }

    public function viderPanier()
    {
        foreach ($this->contenu as $idArticle => $qteArticle) {
            unset($this->contenu[$idArticle]);
        }
    }
}