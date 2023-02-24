<?php
namespace ModelBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
/** 
 * @ORM\Entity 
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="role", type="string")
 * @ORM\DiscriminatorMap({"user" = "User","admin" = "Admin", "customer" = "Customer", "store_manager" = "StoreManager", "store_chain_manager" = "StoreChainManager"})
 * @ORM\HasLifecycleCallbacks 
 * @ORM\Entity(repositoryClass="ModelBundle\Repository\UserRepository")
 * @ExclusionPolicy("all")
 */
class User
{
  
     /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Expose
     * @Groups({"shortDataCustomerGroup","othersPostGroup","commentGroup"})
     */
    protected $id;

     /**
     * @var string
     *
     * @ORM\Column(name="firstname", type="string", length=255, nullable=false)
     * @Expose
     * @Groups({"shortDataCustomerGroup","othersPostGroup","commentGroup"})
     */
    protected $firstname;

    /**
     * @var string
     *
     * @ORM\Column(name="lastname", type="string", length=255, nullable=false)
     * @Expose
     * @Groups({"shortDataCustomerGroup","othersPostGroup","commentGroup"})
     */
    protected $lastname;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=false, unique=true)
     * @Expose
     * @Groups({"shortDataCustomerGroup","othersPostGroup"})
     */
    protected $email;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255, nullable=true, unique=false)
     */
    protected $password;

       /**
     * @var string
     *
     * @ORM\Column(name="password_token", type="string", length=255, nullable=true, unique=false)
     */
    protected $passwordToken;

    /**
     * @var string
     *
     * @ORM\Column(name="photo",type="string",length=255,nullable=true)
     * @Assert\File(maxSize = "5M",maxSizeMessage = "Max size of file is 5MB.",mimeTypesMessage = "There are only allowed jpeg, gif and png images",mimeTypes={ "image/png","image/jpeg","image/jpg" })
     * @Expose
     * @Groups({"shortDataCustomerGroup","othersPostGroup","commentGroup"})
     */
    
    protected $photo;

     /**
     * @var string
     *
     * @ORM\Column(name="phone",type="string",nullable=true)
     * @Expose
     * @Groups({"shortDataCustomerGroup"})
     */
    protected $phone;

    /**
     * @var boolean
     *
     * @ORM\Column(name="verifed",type="boolean", nullable=false,options={"default":false})
     * @Expose
     * @Groups({"shortDataCustomerGroup"})
     */
    protected $verifed;
    /**
     * @var boolean
     *
     * @ORM\Column(name="activated",type="boolean", nullable=false,options={"default":false})
     * @Expose
     * @Groups({"shortDataCustomerGroup"})
     */
    protected $activated;

    /**
     * @var string
     *
     * @ORM\Column(name="token_verified", type="string", length=255, nullable=true, unique=true)
     */
    protected $tokenVerified;

     /**
     * @var string
     *
     * @ORM\Column(name="token_reset", type="string", length=255, nullable=true, unique=true)
     */
    protected $tokenReset;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $createdAt;

    /**
    * @ORM\Column(type="datetime")
    */
    protected $updatedAt;

    /**
    *
    * @ORM\PrePersist
    * @ORM\PreUpdate
    */
    public function updatedTimestamps()
    {  
        $date=new \DateTime('now');
        $date->setTimezone(new \DateTimeZone('GMT'));
        $this->setUpdatedAt($date);

        if ($this->getCreatedAt() == null) {
            $this->setCreatedAt($date);
        }
    }

     /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Advertisement
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Advertisement
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

     /**
     * Constructor
     */
    public function __construct()
    {
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

     /**
     * Set passwordToken
     *
     * @param string $passwordToken
     *
     * @return Admin
     */
    public function setPasswordToken($passwordToken)
    {
        $this->passwordToken = $passwordToken;

        return $this;
    }

    /**
     * Get passwordToken
     *
     * @return string
     */
    public function getPasswordToken()
    {
        return $this->passwordToken;
    }

    /**
     * @inheritDoc
     */
    public function getRoles()
    {
        return array('ROLE_USER');
    }
    
     public $path;

}
?>