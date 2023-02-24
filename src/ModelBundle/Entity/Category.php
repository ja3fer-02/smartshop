<?php

namespace ModelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Category
 *
 * @ORM\Table(name="category")
 * @ORM\Entity(repositoryClass="ModelBundle\Repository\CategoryRepository")
 * @ORM\HasLifecycleCallbacks 
 */
class Category
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="categoryName", type="string", length=255)
     */
    private $categoryName;

    /**
     * @var string
     *
     * @ORM\Column(name="categoryIcon", type="string", length=255)
     */
    private $categoryIcon;

    /**
     * Many Categories have Many Advertisements.
     * @ORM\ManyToMany(targetEntity="Advertisement", mappedBy="advertisementCategories")
     */
    private $advertisements;

    /**
     * One Category has Many Products.
     * @ORM\OneToMany(targetEntity="Product", mappedBy="productCategory")
     */
    private $categoryProducts;

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
        $this->advertisements = new \Doctrine\Common\Collections\ArrayCollection();
        $this->categoryProducts = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set categoryName
     *
     * @param string $categoryName
     *
     * @return Category
     */
    public function setCategoryName($categoryName)
    {
        $this->categoryName = $categoryName;

        return $this;
    }

    /**
     * Get categoryName
     *
     * @return string
     */
    public function getCategoryName()
    {
        return $this->categoryName;
    }

    /**
     * Set categoryIcon
     *
     * @param string $categoryIcon
     *
     * @return Category
     */
    public function setCategoryIcon($categoryIcon)
    {
        $this->categoryIcon = $categoryIcon;

        return $this;
    }

    /**
     * Get categoryIcon
     *
     * @return string
     */
    public function getCategoryIcon()
    {
        return $this->categoryIcon;
    }

    /**
     * Add advertisement
     *
     * @param \ModelBundle\Entity\Advertisement $advertisement
     *
     * @return Category
     */
    public function addAdvertisement(\ModelBundle\Entity\Advertisement $advertisement)
    {
        $this->advertisements[] = $advertisement;

        return $this;
    }

    /**
     * Remove advertisement
     *
     * @param \ModelBundle\Entity\Advertisement $advertisement
     */
    public function removeAdvertisement(\ModelBundle\Entity\Advertisement $advertisement)
    {
        $this->advertisements->removeElement($advertisement);
    }

    /**
     * Get advertisements
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAdvertisements()
    {
        return $this->advertisements;
    }

    /**
     * Add categoryProduct
     *
     * @param \ModelBundle\Entity\Product $categoryProduct
     *
     * @return Category
     */
    public function addCategoryProduct(\ModelBundle\Entity\Product $categoryProduct)
    {
        $this->categoryProducts[] = $categoryProduct;

        return $this;
    }

    /**
     * Remove categoryProduct
     *
     * @param \ModelBundle\Entity\Product $categoryProduct
     */
    public function removeCategoryProduct(\ModelBundle\Entity\Product $categoryProduct)
    {
        $this->categoryProducts->removeElement($categoryProduct);
    }

    /**
     * Get categoryProducts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCategoryProducts()
    {
        return $this->categoryProducts;
    }
}
