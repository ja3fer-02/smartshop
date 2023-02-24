<?php

namespace ModelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use ModelBundle\Entity\User;

use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups;
use Symfony\Component\Security\Core\User\UserInterface;
/**
 * Customer
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="ModelBundle\Repository\UserRepository")
 * @ExclusionPolicy("all")
 */
class Customer extends User implements UserInterface
{
     
     
    /**
     * @var string
     *
     * @ORM\Column(name="country_code", type="string", length=255,nullable=true)
     * @Expose
     * @Groups({"shortDataCustomerGroup"})
     */
    private $countryCode;

    /**
     * @var string
     *
     * @ORM\Column(name="facebook_id", type="string", length=255, nullable=false)
     * @Expose
     * @Groups({"shortDataCustomerGroup"})
     */
    private $facebookId;

     /**
     * @var integer
     *
     * @ORM\Column(name="points", type="integer", nullable=false)
     * @Expose
     * @Groups({"shortDataCustomerGroup"})
     */
    private $points;

    /**
     * @var boolean
     *
     * @ORM\Column(name="activate_notification", type="boolean" )
     * @Expose
     * @Groups({"shortDataCustomerGroup"})
     */
    private $activateNotification;

     /**
     * One Customer has Many UserLoyaltyCards.
     * @ORM\OneToMany(targetEntity="UserLoyaltyCard", mappedBy="userLoyaltyCardCustomer")
     */
    private $customerUserLoyaltyCards;
   
    /**
     * One Customer has One Location.
     * @ORM\OneToOne(targetEntity="Location")
     * @ORM\JoinColumn(name="location_id", referencedColumnName="id")
     * @Expose
     * @Groups({"shortDataCustomerGroup"})
     */
    private $CustomerLocation;

     /**
     * Many Customers liked Many Promotions.
     * @ORM\ManyToMany(targetEntity="Promotion", inversedBy="promotionCustomersLiked")
     * @ORM\JoinTable(name="customer_promotion_like")
     */
    private $customerPromotionsLiked;

    /**
     * Many Customers favored Many Promotions.
     * @ORM\ManyToMany(targetEntity="Promotion", inversedBy="promotionCustomersFavored")
     * @ORM\JoinTable(name="customer_promotion_favoris")
     * @Expose
     * @Groups({"favoriteCustomerGroup"})
     */
    private $customerPromotionsFavored;

    /**
     * One Customer has Many FollowStates.
     * @ORM\OneToMany(targetEntity="FollowState", mappedBy="followerStateCustomer")
     */
    private $customerFollowersState;

    /**
     * One Customer has Many FollowStates.
     * @ORM\OneToMany(targetEntity="FollowState", mappedBy="followedStateCustomer")
     */
    private $customerFollowedsState;

    /**
     * Many Customers View Many Advertissements.
     * @ORM\ManyToMany(targetEntity="Advertisement")
     * @ORM\JoinTable(name="customer_advertisement",
     *      joinColumns={@ORM\JoinColumn(name="advertisement_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="customer_id", referencedColumnName="id")}
     *      )
     */
    private $customerAdvertisementsView;
  
    /**
     * Many Customers likes Many Posts.
     * @ORM\ManyToMany(targetEntity="Post", inversedBy="customerPostsLiked")
     * @ORM\JoinTable(name="customers_posts_like")
     */
    private $postCustomersLiked;

    /**
     * One Customer can share Many Posts.
     * @ORM\OneToMany(targetEntity="Post", mappedBy="postSharedCustomer")
     * @Expose
     * @Groups({"myPostGroup"})
     */
    private $customerPostsShared;

    /**
     * One Customer can comment Many Comments.
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="commentCustomer")
     */
    private $customerComments;

    /**
     * One Customer has Many UserBonus.
     * @ORM\OneToMany(targetEntity="UserBonus", mappedBy="userBonusCustomer")
     */
    private $customerUserBonus;

    /**
     * One Customer has Many DeviceToken.
     * @ORM\OneToMany(targetEntity="DeviceToken", mappedBy="deviceTokenCustomer", cascade={"persist", "remove"})
     */
    private $customerDeviceToken;

    /**
     * Many Customers can favorise many Products.
     * @ORM\ManyToMany(targetEntity="Product", inversedBy="productCustomersFavored")
     * @ORM\JoinTable(name="customer_product_favoris")
    * @Expose
     * @Groups({"favoriteCustomerGroup"})
     */
    private $customerProductsFavored;

     /**
     * @var float
     *
     * @ORM\Column(name="longitude", type="float")
     * @Expose
     * @Groups({"shortDataCustomerGroup"})
     */
    private $longitude;

    /**
     * @var float
     *
     * @ORM\Column(name="latitude", type="float")
     * @Expose
     * @Groups({"shortDataCustomerGroup"})
     */
    private $latitude;



    /**
     * Constructor
     */
    public function __construct($username)
    {
        $this->customerUserLoyaltyCards   = new \Doctrine\Common\Collections\ArrayCollection();
        $this->customerPromotionsLiked    = new \Doctrine\Common\Collections\ArrayCollection();
        $this->customerPromotionsFavored  = new \Doctrine\Common\Collections\ArrayCollection();
        $this->customerFollowersState     = new \Doctrine\Common\Collections\ArrayCollection();
        $this->customerFollowedsState     = new \Doctrine\Common\Collections\ArrayCollection();
        $this->customerAdvertisementsView = new \Doctrine\Common\Collections\ArrayCollection();
        $this->postCustomersLiked         = new \Doctrine\Common\Collections\ArrayCollection();
        $this->customerPostsShared        = new \Doctrine\Common\Collections\ArrayCollection();
        $this->customerComments           = new \Doctrine\Common\Collections\ArrayCollection();
        $this->customerUserBonus          = new \Doctrine\Common\Collections\ArrayCollection();
        $this->customerProductsFavored    = new \Doctrine\Common\Collections\ArrayCollection();
        $this->customerDeviceToken        = new \Doctrine\Common\Collections\ArrayCollection();
        $this->username                   = $username;
    }

    /**
     * Set countryCode
     *
     * @param string $countryCode
     *
     * @return Customer
     */
    public function setCountryCode($countryCode)
    {
        $this->countryCode = $countryCode;

        return $this;
    }

    /**
     * Get countryCode
     *
     * @return string
     */
    public function getCountryCode()
    {
        return $this->countryCode;
    }

    /**
     * Set facebookId
     *
     * @param string $facebookId
     *
     * @return Customer
     */
    public function setFacebookId($facebookId)
    {
        $this->facebookId = $facebookId;

        return $this;
    }

    /**
     * Get facebookId
     *
     * @return string
     */
    public function getFacebookId()
    {
        return $this->facebookId;
    }

    /**
     * Get points
     *
     * @return integer
     */
    public function getPoints()
    {
        return $this->points;
    }

     /**
     * Set points
     *
     * @param integer $points
     *
     * @return Customer
     */
    public function setPoints($points)
    {
        $this->points = $points;

        return $this;
    }

    /**
     * Set activateNotification
     *
     * @param boolean $activateNotification
     *
     * @return Customer
     */
    public function setActivateNotification($activateNotification)
    {
        $this->activateNotification = $activateNotification;

        return $this;
    }

    /**
     * Get activateNotification
     *
     * @return boolean
     */
    public function getActivateNotification()
    {
        return $this->activateNotification;
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
     * @return Customer
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
     * @return Customer
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
     * @return Customer
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
     * @return Customer
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
        return md5($this->email);
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPlainPassword()
    {
        return $this->password;
    }


    /**
     * Set photo
     *
     * @param string $photo
     *
     * @return Customer
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
     * @return Customer
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
     * @return Customer
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
     * @return Customer
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
     * @return Customer
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
     * @return Customer
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
     * Add customerUserLoyaltyCard
     *
     * @param \ModelBundle\Entity\UserLoyaltyCard $customerUserLoyaltyCard
     *
     * @return Customer
     */
    public function addCustomerUserLoyaltyCard(\ModelBundle\Entity\UserLoyaltyCard $customerUserLoyaltyCard)
    {
        $this->customerUserLoyaltyCards[] = $customerUserLoyaltyCard;

        return $this;
    }

    /**
     * Remove customerUserLoyaltyCard
     *
     * @param \ModelBundle\Entity\UserLoyaltyCard $customerUserLoyaltyCard
     */
    public function removeCustomerUserLoyaltyCard(\ModelBundle\Entity\UserLoyaltyCard $customerUserLoyaltyCard)
    {
        $this->customerUserLoyaltyCards->removeElement($customerUserLoyaltyCard);
    }

    /**
     * Get customerUserLoyaltyCards
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCustomerUserLoyaltyCards()
    {
        return $this->customerUserLoyaltyCards;
    }

    /**
     * Set customerLocation
     *
     * @param \ModelBundle\Entity\Location $customerLocation
     *
     * @return Customer
     */
    public function setCustomerLocation(\ModelBundle\Entity\Location $customerLocation = null)
    {
        $this->CustomerLocation = $customerLocation;

        return $this;
    }

    /**
     * Get customerLocation
     *
     * @return \ModelBundle\Entity\Location
     */
    public function getCustomerLocation()
    {
        return $this->CustomerLocation;
    }

    /**
     * Add customerPromotionsLiked
     *
     * @param \ModelBundle\Entity\Promotion $customerPromotionsLiked
     *
     * @return Customer
     */
    public function addCustomerPromotionsLiked(\ModelBundle\Entity\Promotion $customerPromotionsLiked)
    {
        $this->customerPromotionsLiked[] = $customerPromotionsLiked;

        return $this;
    }

    /**
     * Remove customerPromotionsLiked
     *
     * @param \ModelBundle\Entity\Promotion $customerPromotionsLiked
     */
    public function removeCustomerPromotionsLiked(\ModelBundle\Entity\Promotion $customerPromotionsLiked)
    {
        $this->customerPromotionsLiked->removeElement($customerPromotionsLiked);
    }

    /**
     * Get customerPromotionsLiked
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCustomerPromotionsLiked()
    {
        return $this->customerPromotionsLiked;
    }

    /**
     * Add customerPromotionsFavored
     *
     * @param \ModelBundle\Entity\Promotion $customerPromotionsFavored
     *
     * @return Customer
     */
    public function addCustomerPromotionsFavored(\ModelBundle\Entity\Promotion $customerPromotionsFavored)
    {
        $this->customerPromotionsFavored[] = $customerPromotionsFavored;

        return $this;
    }

    /**
     * Remove customerPromotionsFavored
     *
     * @param \ModelBundle\Entity\Promotion $customerPromotionsFavored
     */
    public function removeCustomerPromotionsFavored(\ModelBundle\Entity\Promotion $customerPromotionsFavored)
    {
        $this->customerPromotionsFavored->removeElement($customerPromotionsFavored);
    }

    /**
     * Get customerPromotionsFavored
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCustomerPromotionsFavored()
    {
        return $this->customerPromotionsFavored;
    }

    /**
     * Add customerFollowersState
     *
     * @param \ModelBundle\Entity\FollowState $customerFollowersState
     *
     * @return Customer
     */
    public function addCustomerFollowersState(\ModelBundle\Entity\FollowState $customerFollowersState)
    {
        $this->customerFollowersState[] = $customerFollowersState;

        return $this;
    }

    /**
     * Remove customerFollowersState
     *
     * @param \ModelBundle\Entity\FollowState $customerFollowersState
     */
    public function removeCustomerFollowersState(\ModelBundle\Entity\FollowState $customerFollowersState)
    {
        $this->customerFollowersState->removeElement($customerFollowersState);
    }

    /**
     * Get customerFollowersState
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCustomerFollowersState()
    {
        return $this->customerFollowersState;
    }

    /**
     * Add customerFollowedsState
     *
     * @param \ModelBundle\Entity\FollowState $customerFollowedsState
     *
     * @return Customer
     */
    public function addCustomerFollowedsState(\ModelBundle\Entity\FollowState $customerFollowedsState)
    {
        $this->customerFollowedsState[] = $customerFollowedsState;

        return $this;
    }

    /**
     * Remove customerFollowedsState
     *
     * @param \ModelBundle\Entity\FollowState $customerFollowedsState
     */
    public function removeCustomerFollowedsState(\ModelBundle\Entity\FollowState $customerFollowedsState)
    {
        $this->customerFollowedsState->removeElement($customerFollowedsState);
    }

    /**
     * Get customerFollowedsState
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCustomerFollowedsState()
    {
        return $this->customerFollowedsState;
    }

    /**
     * Add customerAdvertisementsView
     *
     * @param \ModelBundle\Entity\Advertisement $customerAdvertisementsView
     *
     * @return Customer
     */
    public function addCustomerAdvertisementsView(\ModelBundle\Entity\Advertisement $customerAdvertisementsView)
    {
        $this->customerAdvertisementsView[] = $customerAdvertisementsView;

        return $this;
    }

    /**
     * Remove customerAdvertisementsView
     *
     * @param \ModelBundle\Entity\Advertisement $customerAdvertisementsView
     */
    public function removeCustomerAdvertisementsView(\ModelBundle\Entity\Advertisement $customerAdvertisementsView)
    {
        $this->customerAdvertisementsView->removeElement($customerAdvertisementsView);
    }

    /**
     * Get customerAdvertisementsView
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCustomerAdvertisementsView()
    {
        return $this->customerAdvertisementsView;
    }

    /**
     * Add postCustomersLiked
     *
     * @param \ModelBundle\Entity\Post $postCustomersLiked
     *
     * @return Customer
     */
    public function addPostCustomersLiked(\ModelBundle\Entity\Post $postCustomersLiked)
    {
        $this->postCustomersLiked[] = $postCustomersLiked;

        return $this;
    }

    /**
     * Remove postCustomersLiked
     *
     * @param \ModelBundle\Entity\Post $postCustomersLiked
     */
    public function removePostCustomersLiked(\ModelBundle\Entity\Post $postCustomersLiked)
    {
        $this->postCustomersLiked->removeElement($postCustomersLiked);
    }

    /**
     * Get postCustomersLiked
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPostCustomersLiked()
    {
        return $this->postCustomersLiked;
    }

    /**
     * Add customerPostsShared
     *
     * @param \ModelBundle\Entity\Post $customerPostsShared
     *
     * @return Customer
     */
    public function addCustomerPostsShared(\ModelBundle\Entity\Post $customerPostsShared)
    {
        $this->customerPostsShared[] = $customerPostsShared;

        return $this;
    }

    /**
     * Remove customerPostsShared
     *
     * @param \ModelBundle\Entity\Post $customerPostsShared
     */
    public function removeCustomerPostsShared(\ModelBundle\Entity\Post $customerPostsShared)
    {
        $this->customerPostsShared->removeElement($customerPostsShared);
    }

    /**
     * Get customerPostsShared
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCustomerPostsShared()
    {
        return $this->customerPostsShared;
    }

    /**
     * Add customerComment
     *
     * @param \ModelBundle\Entity\Comment $customerComment
     *
     * @return Customer
     */
    public function addCustomerComment(\ModelBundle\Entity\Comment $customerComment)
    {
        $this->customerComments[] = $customerComment;

        return $this;
    }

    /**
     * Remove customerComment
     *
     * @param \ModelBundle\Entity\Comment $customerComment
     */
    public function removeCustomerComment(\ModelBundle\Entity\Comment $customerComment)
    {
        $this->customerComments->removeElement($customerComment);
    }

    /**
     * Get customerComments
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCustomerComments()
    {
        return $this->customerComments;
    }

    /**
     * Add customerUserBonus
     *
     * @param \ModelBundle\Entity\UserBonus $customerUserBonus
     *
     * @return Customer
     */
    public function addCustomerUserBonus(\ModelBundle\Entity\UserBonus $customerUserBonus)
    {
        $this->customerUserBonus[] = $customerUserBonus;

        return $this;
    }

    /**
     * Remove customerUserBonus
     *
     * @param \ModelBundle\Entity\UserBonus $customerUserBonus
     */
    public function removeCustomerUserBonus(\ModelBundle\Entity\UserBonus $customerUserBonus)
    {
        $this->customerUserBonus->removeElement($customerUserBonus);
    }

    /**
     * Get customerUserBonus
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCustomerUserBonus()
    {
        return $this->customerUserBonus;
    }

    /**
     * Add customerProductsFavored
     *
     * @param \ModelBundle\Entity\Product $customerProductsFavored
     *
     * @return Customer
     */
    public function addCustomerProductsFavored(\ModelBundle\Entity\Product $customerProductsFavored)
    {
        $this->customerProductsFavored[] = $customerProductsFavored;

        return $this;
    }

    /**
     * Remove customerProductsFavored
     *
     * @param \ModelBundle\Entity\Product $customerProductsFavored
     */
    public function removeCustomerProductsFavored(\ModelBundle\Entity\Product $customerProductsFavored)
    {
        $this->customerProductsFavored->removeElement($customerProductsFavored);
    }

    /**
     * Get customerProductsFavored
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCustomerProductsFavored()
    {
        return $this->customerProductsFavored;
    }

    public function getUsername()
    {
        return $this->email;
    }

     public function getSalt()
    {
        return null;
    }
   
   public function getRole()
    {
        return "customer";
    }

    public function getRoles()
    {
        return array('ROLE_USER');
    }

    public function eraseCredentials()
    {
    }

    /**
     * Set longitude
     *
     * @param float $longitude
     *
     * @return Location
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return float
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set latitude
     *
     * @param float $latitude
     *
     * @return Location
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude
     *
     * @return float
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Add customerDeviceToken
     *
     * @param \ModelBundle\Entity\DeviceToken $customerDeviceToken
     *
     * @return Customer
     */
    public function addCustomerDeviceToken(\ModelBundle\Entity\DeviceToken $customerDeviceToken)
    {
        $this->customerDeviceToken[] = $customerDeviceToken;

        return $this;
    }

    /**
     * Remove customerDeviceToken
     *
     * @param \ModelBundle\Entity\DeviceToken $customerDeviceToken
     */
    public function removeCustomerDeviceToken(\ModelBundle\Entity\DeviceToken $customerDeviceToken)
    {
        $this->customerDeviceToken->removeElement($customerDeviceToken);
    }

    /**
     * Get customerDeviceToken
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCustomerDeviceToken()
    {
        return $this->customerDeviceToken;
    }

    
}
