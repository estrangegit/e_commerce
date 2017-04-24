<?php

namespace sil08\VitrineBundle\Entity;

/**
 * Avis
 */
class Avis
{
    /**
     * @var integer
     */
    private $note;

    /**
     * @var string
     */
    private $commentaire;

    /**
     * @var \sil08\VitrineBundle\Entity\Article
     */
    private $article;

    /**
     * @var \sil08\VitrineBundle\Entity\Client
     */
    private $client;


    /**
     * Set note
     *
     * @param integer $note
     *
     * @return Avis
     */
    public function setNote($note)
    {
        $this->note = $note;

        return $this;
    }

    /**
     * Get note
     *
     * @return integer
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * Set commentaire
     *
     * @param string $commentaire
     *
     * @return Avis
     */
    public function setCommentaire($commentaire)
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    /**
     * Get commentaire
     *
     * @return string
     */
    public function getCommentaire()
    {
        return $this->commentaire;
    }

    /**
     * Set article
     *
     * @param \sil08\VitrineBundle\Entity\Article $article
     *
     * @return Avis
     */
    public function setArticle(\sil08\VitrineBundle\Entity\Article $article)
    {
        $this->article = $article;

        return $this;
    }

    /**
     * Get article
     *
     * @return \sil08\VitrineBundle\Entity\Article
     */
    public function getArticle()
    {
        return $this->article;
    }

    /**
     * Set client
     *
     * @param \sil08\VitrineBundle\Entity\Client $client
     *
     * @return Avis
     */
    public function setClient(\sil08\VitrineBundle\Entity\Client $client)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Get client
     *
     * @return \sil08\VitrineBundle\Entity\Client
     */
    public function getClient()
    {
        return $this->client;
    }
    /**
     * @var integer
     */
    private $id;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
}
