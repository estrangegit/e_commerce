<?php

namespace sil08\VitrineBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

class Article
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $libelle;

    /**
     * @var string
     */
    private $prix;

    /**
     * @var integer
     */
    private $stock;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $commandes_articles;

    /**
     * @var \sil08\VitrineBundle\Entity\Categorie
     */
    private $categorie;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->commandes_articles = new \Doctrine\Common\Collections\ArrayCollection();
        $this->avis = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set libelle
     *
     * @param string $libelle
     *
     * @return Article
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * Get libelle
     *
     * @return string
     */
    public function getLibelle()
    {
        return $this->libelle;
    }

    /**
     * Set prix
     *
     * @param string $prix
     *
     * @return Article
     */
    public function setPrix($prix)
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * Get prix
     *
     * @return string
     */
    public function getPrix()
    {
        return $this->prix;
    }

    /**
     * Set stock
     *
     * @param integer $stock
     *
     * @return Article
     */
    public function setStock($stock)
    {
        $this->stock = $stock;

        return $this;
    }

    /**
     * Get stock
     *
     * @return integer
     */
    public function getStock()
    {
        return $this->stock;
    }

    /**
     * Add commandesArticle
     *
     * @param \sil08\VitrineBundle\Entity\CommandeArticle $commandesArticle
     *
     * @return Article
     */
    public function addCommandesArticle(\sil08\VitrineBundle\Entity\CommandeArticle $commandesArticle)
    {
        $this->commandes_articles[] = $commandesArticle;

        return $this;
    }

    /**
     * Remove commandesArticle
     *
     * @param \sil08\VitrineBundle\Entity\CommandeArticle $commandesArticle
     */
    public function removeCommandesArticle(\sil08\VitrineBundle\Entity\CommandeArticle $commandesArticle)
    {
        $this->commandes_articles->removeElement($commandesArticle);
    }

    /**
     * Get commandesArticles
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCommandesArticles()
    {
        return $this->commandes_articles;
    }

    /**
     * Set categorie
     *
     * @param \sil08\VitrineBundle\Entity\Categorie $categorie
     *
     * @return Article
     */
    public function setCategorie(\sil08\VitrineBundle\Entity\Categorie $categorie = null)
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * Get categorie
     *
     * @return \sil08\VitrineBundle\Entity\Categorie
     */
    public function getCategorie()
    {
        return $this->categorie;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $avis;


    /**
     * Add avi
     *
     * @param \sil08\VitrineBundle\Entity\Avis $avi
     *
     * @return Article
     */
    public function addAvi(\sil08\VitrineBundle\Entity\Avis $avi)
    {
        $this->avis[] = $avi;

        return $this;
    }

    /**
     * Remove avi
     *
     * @param \sil08\VitrineBundle\Entity\Avis $avi
     */
    public function removeAvi(\sil08\VitrineBundle\Entity\Avis $avi)
    {
        $this->avis->removeElement($avi);
    }

    /**
     * Get avis
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAvis()
    {
        return $this->avis;
    }
    /**
     * @var string
     */
    private $image;


    /**
     * Set image
     *
     * @param string $image
     *
     * @return Article
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }
}
