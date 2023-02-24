<?php

namespace ModelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\ExclusionPolicy;
/**
 * Product
 *
 * @ORM\Table(name="product")
 * @ORM\Entity(repositoryClass="ModelBundle\Repository\ProductRepository")
 * @ORM\HasLifecycleCallbacks 
 * @ExclusionPolicy("all")
 */
class Product
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
     * @var string
     *
     * @ORM\Column(name="productDescription", type="string", length=255)
     * @Expose
     * @Groups({"fullDataStoreProductGroup","favoriteCustomerGroup"})
     */
    private $productDescription;

    /**
     * @var string
     *
     * @ORM\Column(name="productName", type="string", length=255)
     * @Expose
     * @Groups({"fullDataStoreProductGroup","favoriteCustomerGroup"})
     */
    private $productName;

    /**
     * @var string
     *
     * @ORM\Column(name="productImage", type="string", length=255)
     * @Expose
     * @Groups({"fullDataStoreProductGroup","favoriteCustomerGroup"})
     */
    private $productImage;

    /**
     * @var float
     *
     * @ORM\Column(name="productInitialPrice", type="float")
     * @Expose
     * @Groups({"fullDataStoreProductGroup","favoriteCustomerGroup"})
     */
    private $productInitialPrice;

    /**
     * @var bool
     * @Expose
     * @ORM\Column(name="productAvailable", type="boolean")
     */
    private $productAvailable;

    /**
     * Many Products have One Categories.
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="categoryProducts")
     * @Expose
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     */
    private $productCategory;

    /**
     * One Product has Many storeProducts.
     * @ORM\OneToMany(targetEntity="StoreProduct", mappedBy="storeProductProduct")
     * @Expose
     * @Groups({"shortDataStoreProductsGroup"})
     */
    private $productStoreProducts;

    /**
     * Many Products can be favorised by many Customers.
     * @ORM\ManyToMany(targetEntity="Customer", mappedBy="customerProductsFavored")
     */
    private $productCustomersFavored;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
    * @ORM\Column(type="datetime")
    */
    private $updatedAt;

    /**
     * @var string
     *
     * @ORM\Column(name="barcode", type="string", length=255)
     * @Expose
     * @Groups({"shortDataUserLoyaltyCardGroup"})
     */
    private $barcode;


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
        $this->productStoreProducts = new \Doctrine\Common\Collections\ArrayCollection();
        $this->productCustomersFavored = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set productDescription
     *
     * @param string $productDescription
     *
     * @return Product
     */
    public function setProductDescription($productDescription)
    {
        $this->productDescription = $productDescription;

        return $this;
    }

    /**
     * Get productDescription
     *
     * @return string
     */
    public function getProductDescription()
    {
        return $this->productDescription;
    }

    /**
     * Set productName
     *
     * @param string $productName
     *
     * @return Product
     */
    public function setProductName($productName)
    {
        $this->productName = $productName;

        return $this;
    }

    /**
     * Get productName
     *
     * @return string
     */
    public function getProductName()
    {
        return $this->productName;
    }

    /**
     * Set productImage
     *
     * @param string $productImage
     *
     * @return Product
     */
    public function setProductImage($productImage)
    {
        $this->productImage = $productImage;

        return $this;
    }

    /**
     * Get productImage
     *
     * @return string
     */
    public function getProductImage()
    {
        return $this->productImage;
    }

    /**
     * Set productInitialPrice
     *
     * @param float $productInitialPrice
     *
     * @return Product
     */
    public function setProductInitialPrice($productInitialPrice)
    {
        $this->productInitialPrice = $productInitialPrice;

        return $this;
    }

    /**
     * Get productInitialPrice
     *
     * @return float
     */
    public function getProductInitialPrice()
    {
        return $this->productInitialPrice;
    }

    /**
     * Set productAvailable
     *
     * @param boolean $productAvailable
     *
     * @return Product
     */
    public function setProductAvailable($productAvailable)
    {
        $this->productAvailable = $productAvailable;

        return $this;
    }

    /**
     * Get productAvailable
     *
     * @return boolean
     */
    public function getProductAvailable()
    {
        return $this->productAvailable;
    }

    /**
     * Set productCategory
     *
     * @param \ModelBundle\Entity\Category $productCategory
     *
     * @return Product
     */
    public function setProductCategory(\ModelBundle\Entity\Category $productCategory = null)
    {
        $this->productCategory = $productCategory;

        return $this;
    }

    /**
     * Get productCategory
     *
     * @return \ModelBundle\Entity\Category
     */
    public function getProductCategory()
    {
        return $this->productCategory;
    }

    /**
     * Add productStoreProduct
     *
     * @param \ModelBundle\Entity\StoreProduct $productStoreProduct
     *
     * @return Product
     */
    public function addProductStoreProduct(\ModelBundle\Entity\StoreProduct $productStoreProduct)
    {
        $this->productStoreProducts[] = $productStoreProduct;

        return $this;
    }

    /**
     * Remove productStoreProduct
     *
     * @param \ModelBundle\Entity\StoreProduct $productStoreProduct
     */
    public function removeProductStoreProduct(\ModelBundle\Entity\StoreProduct $productStoreProduct)
    {
        $this->productStoreProducts->removeElement($productStoreProduct);
    }

    /**
     * Get productStoreProducts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProductStoreProducts()
    {
        return $this->productStoreProducts;
    }

    /**
     * Add productCustomersFavored
     *
     * @param \ModelBundle\Entity\Customer $productCustomersFavored
     *
     * @return Product
     */
    public function addProductCustomersFavored(\ModelBundle\Entity\Customer $productCustomersFavored)
    {
        $this->productCustomersFavored[] = $productCustomersFavored;

        return $this;
    }

    /**
     * Remove productCustomersFavored
     *
     * @param \ModelBundle\Entity\Customer $productCustomersFavored
     */
    public function removeProductCustomersFavored(\ModelBundle\Entity\Customer $productCustomersFavored)
    {
        $this->productCustomersFavored->removeElement($productCustomersFavored);
    }

    /**
     * Get productCustomersFavored
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProductCustomersFavored()
    {
        return $this->productCustomersFavored;
    }

     /**
     * Set barcode
     *
     * @param string $barcode
     *
     * @return UserLoyaltyCard
     */
    public function setBarcode($barcode)
    {
        $this->barcode = $barcode;

        return $this;
    }

    /**
     * Get barcode
     *
     * @return string
     */
    public function getBarcode()
    {
        return $this->barcode;
    }

}
