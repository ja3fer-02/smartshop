<?php

namespace ModelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use ModelBundle\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;


/**
 * Admin
 * @ORM\Entity
 *
 */
class Admin extends User implements UserInterface

{
     
     
     
    /**
     * Constructor
     */
    public function __construct()
    {
        //$this->role="admin";
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
     * Set firstname
     *
     * @param string $firstname
     *
     * @return Admin
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }
    public function getUsername()
    {
        return null;
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
     * @return Admin
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
     * @return Admin
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
     * @return Admin
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
     * @return Admin
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
     * @return Admin
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
     * @return Admin
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
     * @return Admin
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
     * @return Admin
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
     * @return Admin
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

    public function getRole()
    {
        return "admin";
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
