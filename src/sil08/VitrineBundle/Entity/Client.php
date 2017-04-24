<?php

namespace sil08\VitrineBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;


/**
 * Client
 */
class Client implements UserInterface, \Serializable
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $prenom;

    /**
     * @var string
     */
    private $nom;

    /**
     * @var string
     */
    private $adresse;

    /**
     * @var string
     */
    private $email;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $commandes;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->commandes = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set prenom
     *
     * @param string $prenom
     * @return Client
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string 
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set nom
     *
     * @param string $nom
     * @return Client
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string 
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set adresse
     *
     * @param string $adresse
     * @return Client
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * Get adresse
     *
     * @return string 
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Client
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Add commande
     *
     * @param \sil08\VitrineBundle\Entity\Commande $commande
     * @return Client
     */
    public function addCommande(\sil08\VitrineBundle\Entity\Commande $commande)
    {
        $this->commandes[] = $commande;

        return $this;
    }

    /**
     * Remove commande
     *
     * @param \sil08\VitrineBundle\Entity\Commande $commande
     */
    public function removeCommande(\sil08\VitrineBundle\Entity\Commande $commande)
    {
        $this->commandes->removeElement($commande);
    }

    /**
     * Get commandes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCommandes()
    {
        return $this->commandes;
    }

    public function __toString()
    {
        return $this->getPseudo();
    }

    /**
     * @var string
     */
    private $pseudo;

    /**
     * @var string
     */
    private $motDePasse;


    /**
     * Set pseudo
     *
     * @param string $pseudo
     * @return Client
     */
    public function setPseudo($pseudo)
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    /**
     * Get pseudo
     *
     * @return string 
     */
    public function getPseudo()
    {
        return $this->pseudo;
    }

    /**
     * Set motDePasse
     *
     * @param string $motDePasse
     * @return Client
     */
    public function setMotDePasse($motDePasse)
    {
        $this->motDePasse = $motDePasse;

        return $this;
    }

    /**
     * Get motDePasse
     *
     * @return string 
     */
    public function getMotDePasse()
    {
        return $this->motDePasse;
    }
    /**
     * @var boolean
     */
    private $admin;


    /**
     * Set admin
     *
     * @param boolean $admin
     *
     * @return Client
     */
    public function setAdmin($admin)
    {
        $this->admin = $admin;

        return $this;
    }

    /**
     * Get admin
     *
     * @return boolean
     */
    public function getAdmin()
    {
        return $this->admin;
    }

    public function getUsername() {
        return $this->getPseudo();
    }

    public function getSalt() {
        return null;
    }

    public function getPassword() {
        return $this->getMotDePasse();
    }

    public function getRoles() {
        if ($this->getAdmin())
            return array('ROLE_ADMIN');
        else
            return array('ROLE_USER');
    }

    public function eraseCredentials(){
    }

    public function serialize() {
        return serialize(array($this->id));
    }

    public function unserialize($serialized) {
        list ($this->id) = unserialize($serialized);
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
     * @return Client
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
}
