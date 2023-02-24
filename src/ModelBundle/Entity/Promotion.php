<?php

namespace ModelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups;

/**
 * Promotion
 * @ORM\InheritanceType("JOINED")
 * @ORM\Table(name="promotion")
 * @ORM\Entity(repositoryClass="ModelBundle\Repository\PromotionRepository")
 * @ORM\HasLifecycleCallbacks 
 * @ExclusionPolicy("all")
 */
abstract class Promotion
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Expose
     * @Groups({"shortDataDiscountGroup","favoriteCustomerGroup"})
     */
    private $id;

    /**
     * @var float
     *
     * @ORM\Column(name="newPrice", type="float")
     * @Expose
     * @Groups({"shortDataDiscountGroup","favoriteCustomerGroup"})
     */
    private $newPrice;

    /**
     * @var string
     *
     * @ORM\Column(name="promotionImage", type="string", length=255)
     * @Expose
     * @Groups({"shortDataDiscountGroup","favoriteCustomerGroup"})
     */
    private $promotionImage;

    /**
     * @var bool
     *
     * @ORM\Column(name="promotionIsAvailable", type="boolean")
     */
    private $promotionIsAvailable;

    /**
     * @var int
     *
     * @ORM\Column(name="promotionBonusPointsValue", type="integer")
     * @Expose
     * @Groups({"shortDataDiscountGroup","favoriteCustomerGroup"})
     */
    private $promotionBonusPointsValue;

    /**
     * Many promotions have One storeProduct.
     * @ORM\ManyToOne(targetEntity="StoreProduct", inversedBy="storeProductPromotions")
     * @ORM\JoinColumn(name="store_product_id", referencedColumnName="id")
     * @Expose
     * @Groups({"shortDataDiscountGroup","favoriteCustomerGroup"})
     */
    private $promotionStoreProduct;

    /**
     * Many Promotions have Many Customers.
     * @ORM\ManyToMany(targetEntity="Customer", mappedBy="customerPromotionsLiked")
     */
    private $promotionCustomersLiked;

    /**
     * Many Promotions have Many Customers.
     * @ORM\ManyToMany(targetEntity="Customer", mappedBy="customerPromotionsFavored")
     */
    private $promotionCustomersFavored;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
    * @ORM\Column(type="datetime")
    */
    private $updatedAt;

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
        $this->promotionCustomersLiked = new \Doctrine\Common\Collections\ArrayCollection();
        $this->promotionCustomersFavored = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set newPrice
     *
     * @param float $newPrice
     *
     * @return Promotion
     */
    public function setNewPrice($newPrice)
    {
        $this->newPrice = $newPrice;

        return $this;
    }

    /**
     * Get newPrice
     *
     * @return float
     */
    public function getNewPrice()
    {
        return $this->newPrice;
    }

    /**
     * Set promotionImage
     *
     * @param string $promotionImage
     *
     * @return Promotion
     */
    public function setPromotionImage($promotionImage)
    {
        $this->promotionImage = $promotionImage;

        return $this;
    }

    /**
     * Get promotionImage
     *
     * @return string
     */
    public function getPromotionImage()
    {
        return $this->promotionImage;
    }

    /**
     * Set promotionIsAvailable
     *
     * @param boolean $promotionIsAvailable
     *
     * @return Promotion
     */
    public function setPromotionIsAvailable($promotionIsAvailable)
    {
        $this->promotionIsAvailable = $promotionIsAvailable;

        return $this;
    }

    /**
     * Get promotionIsAvailable
     *
     * @return boolean
     */
    public function getPromotionIsAvailable()
    {
        return $this->promotionIsAvailable;
    }

    /**
     * Set promotionBonusPointsValue
     *
     * @param integer $promotionBonusPointsValue
     *
     * @return Promotion
     */
    public function setPromotionBonusPointsValue($promotionBonusPointsValue)
    {
        $this->promotionBonusPointsValue = $promotionBonusPointsValue;

        return $this;
    }

    /**
     * Get promotionBonusPointsValue
     *
     * @return integer
     */
    public function getPromotionBonusPointsValue()
    {
        return $this->promotionBonusPointsValue;
    }

    /**
     * Set promotionStoreProduct
     *
     * @param \ModelBundle\Entity\StoreProduct $promotionStoreProduct
     *
     * @return Promotion
     */
    public function setPromotionStoreProduct(\ModelBundle\Entity\StoreProduct $promotionStoreProduct = null)
    {
        $this->promotionStoreProduct = $promotionStoreProduct;

        return $this;
    }

    /**
     * Get promotionStoreProduct
     *
     * @return \ModelBundle\Entity\StoreProduct
     */
    public function getPromotionStoreProduct()
    {
        return $this->promotionStoreProduct;
    }

    /**
     * Add promotionCustomersLiked
     *
     * @param \ModelBundle\Entity\Customer $promotionCustomersLiked
     *
     * @return Promotion
     */
    public function addPromotionCustomersLiked(\ModelBundle\Entity\Customer $promotionCustomersLiked)
    {
        $this->promotionCustomersLiked[] = $promotionCustomersLiked;

        return $this;
    }

    /**
     * Remove promotionCustomersLiked
     *
     * @param \ModelBundle\Entity\Customer $promotionCustomersLiked
     */
    public function removePromotionCustomersLiked(\ModelBundle\Entity\Customer $promotionCustomersLiked)
    {
        $this->promotionCustomersLiked->removeElement($promotionCustomersLiked);
    }

    /**
     * Get promotionCustomersLiked
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPromotionCustomersLiked()
    {
        return $this->promotionCustomersLiked;
    }

    /**
     * Add promotionCustomersFavored
     *
     * @param \ModelBundle\Entity\Customer $promotionCustomersFavored
     *
     * @return Promotion
     */
    public function addPromotionCustomersFavored(\ModelBundle\Entity\Customer $promotionCustomersFavored)
    {
        $this->promotionCustomersFavored[] = $promotionCustomersFavored;

        return $this;
    }

    /**
     * Remove promotionCustomersFavored
     *
     * @param \ModelBundle\Entity\Customer $promotionCustomersFavored
     */
    public function removePromotionCustomersFavored(\ModelBundle\Entity\Customer $promotionCustomersFavored)
    {
        $this->promotionCustomersFavored->removeElement($promotionCustomersFavored);
    }

    /**
     * Get promotionCustomersFavored
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPromotionCustomersFavored()
    {
        return $this->promotionCustomersFavored;
    }
}
