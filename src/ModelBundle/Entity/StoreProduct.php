<?php

namespace ModelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\ExclusionPolicy;

/**
 * StoreProduct
 *
 * @ORM\Table(name="store_product")
 * @ORM\Entity(repositoryClass="ModelBundle\Repository\StoreProductRepository")
 * @ORM\HasLifecycleCallbacks 
 * @ExclusionPolicy("all")
 */
class StoreProduct
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Expose
     * @Groups({"fullDataStoreProductGroup","favoriteCustomerGroup"})
     */
    private $id;

    /**
     * @var float
     *
     * @ORM\Column(name="storeProductPrice", type="float")
     * @Expose
     * @Groups({"shortDataStoreGroup","fullDataStoreProductGroup","favoriteCustomerGroup"})
     */
    private $storeProductPrice;

    /**
     * @var string
     *
     * @ORM\Column(name="storeProductImage", type="string", length=255)
     * @Expose
     * @Groups({"fullDataStoreProductGroup","favoriteCustomerGroup"})
     */
    private $storeProductImage;

    /**
     * Many storeProducts have One Product.
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="productStoreProducts")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     * @Expose
     * @Groups({"fullDataStoreProductGroup","favoriteCustomerGroup"})
     */
    private $storeProductProduct;

    /**
     * One storeProduct has Many promotions.
     * @ORM\OneToMany(targetEntity="Promotion", mappedBy="promotionStoreProduct")
     */
    private $storeProductPromotions;
    
    /**
     * Many StoreProducts have One Store.
     * @ORM\ManyToOne(targetEntity="Store", inversedBy="storeProductsStore")
     * @ORM\JoinColumn(name="store_id", referencedColumnName="id")
     */
    private $storeProductStore;


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
        $this->storeProductPromotions = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set storeProductPrice
     *
     * @param float $storeProductPrice
     *
     * @return StoreProduct
     */
    public function setStoreProductPrice($storeProductPrice)
    {
        $this->storeProductPrice = $storeProductPrice;

        return $this;
    }

    /**
     * Get storeProductPrice
     *
     * @return float
     */
    public function getStoreProductPrice()
    {
        return $this->storeProductPrice;
    }

    /**
     * Set storeProductImage
     *
     * @param string $storeProductImage
     *
     * @return StoreProduct
     */
    public function setStoreProductImage($storeProductImage)
    {
        $this->storeProductImage = $storeProductImage;

        return $this;
    }

    /**
     * Get storeProductImage
     *
     * @return string
     */
    public function getStoreProductImage()
    {
        return $this->storeProductImage;
    }

    /**
     * Set storeProductProduct
     *
     * @param \ModelBundle\Entity\Product $storeProductProduct
     *
     * @return StoreProduct
     */
    public function setStoreProductProduct(\ModelBundle\Entity\Product $storeProductProduct = null)
    {
        $this->storeProductProduct = $storeProductProduct;

        return $this;
    }

    /**
     * Get storeProductProduct
     *
     * @return \ModelBundle\Entity\Product
     */
    public function getStoreProductProduct()
    {
        return $this->storeProductProduct;
    }

    /**
     * Add storeProductPromotion
     *
     * @param \ModelBundle\Entity\Promotion $storeProductPromotion
     *
     * @return StoreProduct
     */
    public function addStoreProductPromotion(\ModelBundle\Entity\Promotion $storeProductPromotion)
    {
        $this->storeProductPromotions[] = $storeProductPromotion;

        return $this;
    }

    /**
     * Remove storeProductPromotion
     *
     * @param \ModelBundle\Entity\Promotion $storeProductPromotion
     */
    public function removeStoreProductPromotion(\ModelBundle\Entity\Promotion $storeProductPromotion)
    {
        $this->storeProductPromotions->removeElement($storeProductPromotion);
    }

    /**
     * Get storeProductPromotions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getStoreProductPromotions()
    {
        return $this->storeProductPromotions;
    }

    /**
     * Set storeProductStore
     *
     * @param \ModelBundle\Entity\Store $storeProductStore
     *
     * @return StoreProduct
     */
    public function setStoreProductStore(\ModelBundle\Entity\Store $storeProductStore = null)
    {
        $this->storeProductStore = $storeProductStore;

        return $this;
    }

    /**
     * Get storeProductStore
     *
     * @return \ModelBundle\Entity\Store
     */
    public function getStoreProductStore()
    {
        return $this->storeProductStore;
    }
}
