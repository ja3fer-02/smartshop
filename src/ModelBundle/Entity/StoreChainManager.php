<?php

namespace ModelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use ModelBundle\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;



/**
 * StoreChaineManager
 * @ORM\Entity
 */
class StoreChainManager extends User implements UserInterface
{
 	/**
     * One StoreChainManager manages one StoreChain.
     * @ORM\OneToOne(targetEntity="StoreChain", mappedBy="storeChainManager")
     */
    private $managedstoreChain;
 
    
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
     * Set firstname
     *
     * @param string $firstname
     *
     * @return StoreChainManager
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     *
     * @return StoreChainManager
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return StoreChainManager
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
     * Set password
     *
     * @param string $password
     *
     * @return StoreChainManager
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set photo
     *
     * @param string $photo
     *
     * @return StoreChainManager
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * Get photo
     *
     * @return string
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return StoreChainManager
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set verifed
     *
     * @param boolean $verifed
     *
     * @return StoreChainManager
     */
    public function setVerifed($verifed)
    {
        $this->verifed = $verifed;

        return $this;
    }

    /**
     * Get verifed
     *
     * @return boolean
     */
    public function getVerifed()
    {
        return $this->verifed;
    }

    /**
     * Set activated
     *
     * @param boolean $activated
     *
     * @return StoreChainManager
     */
    public function setActivated($activated)
    {
        $this->activated = $activated;

        return $this;
    }

    /**
     * Get activated
     *
     * @return boolean
     */
    public function getActivated()
    {
        return $this->activated;
    }

    /**
     * Set tokenVerified
     *
     * @param string $tokenVerified
     *
     * @return StoreChainManager
     */
    public function setTokenVerified($tokenVerified)
    {
        $this->tokenVerified = $tokenVerified;

        return $this;
    }

    /**
     * Get tokenVerified
     *
     * @return string
     */
    public function getTokenVerified()
    {
        return $this->tokenVerified;
    }

    /**
     * Set tokenReset
     *
     * @param string $tokenReset
     *
     * @return StoreChainManager
     */
    public function setTokenReset($tokenReset)
    {
        $this->tokenReset = $tokenReset;

        return $this;
    }

    /**
     * Get tokenReset
     *
     * @return string
     */
    public function getTokenReset()
    {
        return $this->tokenReset;
    }

    /**
     * Set managedstoreChain
     *
     * @param \ModelBundle\Entity\StoreChain $managedstoreChain
     *
     * @return StoreChainManager
     */
    public function setManagedstoreChain(\ModelBundle\Entity\StoreChain $managedstoreChain = null)
    {
        $this->managedstoreChain = $managedstoreChain;

        return $this;
    }

    /**
     * Get managedstoreChain
     *
     * @return \ModelBundle\Entity\StoreChain
     */
    public function getManagedstoreChain()
    {
        return $this->managedstoreChain;
    }

    public function getRole()
    {
        return "store_chain_manager";
    }

    public function getUsername()
    {
        return null;
    }
  public function getSalt()
    {
        return null;
    }
   

    public function getRoles()
    {
        return array('ROLE_USER');
    }

    public function eraseCredentials()
    {
    }
    
}
