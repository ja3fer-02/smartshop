<?php

namespace ModelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Advertisement
 *
 * @ORM\Table(name="advertisement")
 * @ORM\Entity(repositoryClass="ModelBundle\Repository\AdvertisementRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Advertisement
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
     * @ORM\Column(name="advertisementType", type="string", length=255)
     */
    private $advertisementType;


    /**
     * @var string
     *
     * @ORM\Column(name="advertisementName", type="string", length=255)
     */
    private $advertisementName;


    /**
     * @var string
     *
     * @ORM\Column(name="advertisementLink", type="string", length=255)
     */
    private $advertisementLink;

    /**
     * @var string
     *
     * @ORM\Column(name="advertisementPhotoLink", type="string", length=255)
     */
    private $advertisementPhotoLink;

    /**
     * @var int
     *
     * @ORM\Column(name="advertisementBonusPointsValue", type="integer")
     */
    private $advertisementBonusPointsValue;


    /**
     * Many Advertisement has One Store.
     * @ORM\ManyToOne(targetEntity="Store", inversedBy="storeAdvertisements")
     */
    private $advertisementStore;

    /**
     * Many Advertisements have Many Categories.
     * @ORM\ManyToMany(targetEntity="Category", inversedBy="advertisements")
     * @ORM\JoinTable(name="advertisements_category")
     */
    private $advertisementCategories;

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
     * Constructor
     */
    public function __construct()
    {
        $this->advertisementCategories = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set advertisementType
     *
     * @param string $advertisementType
     *
     * @return Advertisement
     */
    public function setAdvertisementType($advertisementType)
    {
        $this->advertisementType = $advertisementType;

        return $this;
    }

    /**
     * Get advertisementType
     *
     * @return string
     */
    public function getAdvertisementType()
    {
        return $this->advertisementType;
    }

    /**
     * Set advertisementLink
     *
     * @param string $advertisementLink
     *
     * @return Advertisement
     */
    public function setAdvertisementLink($advertisementLink)
    {
        $this->advertisementLink = $advertisementLink;

        return $this;
    }

    /**
     * Get advertisementLink
     *
     * @return string
     */
    public function getAdvertisementLink()
    {
        return $this->advertisementLink;
    }

    /**
     * Set advertisementName
     *
     * @param string $advertisementName
     *
     * @return Advertisement
     */
    public function setAdvertisementName($advertisementName)
    {
        $this->advertisementName = $advertisementName;

        return $this;
    }

    /**
     * Get advertisementName
     *
     * @return string
     */
    public function getAdvertisementName()
    {
        return $this->advertisementName;
    }

    /**
     * Set advertisementPhotoLink
     *
     * @param string $advertisementPhotoLink
     *
     * @return Advertisement
     */
    public function setAdvertisementPhotoLink($advertisementPhotoLink)
    {
        $this->advertisementPhotoLink = $advertisementPhotoLink;

        return $this;
    }

    /**
     * Get advertisementPhotoLink
     *
     * @return string
     */
    public function getAdvertisementPhotoLink()
    {
        return $this->advertisementPhotoLink;
    }

    /**
     * Set advertisementBonusPointsValue
     *
     * @param integer $advertisementBonusPointsValue
     *
     * @return Advertisement
     */
    public function setAdvertisementBonusPointsValue($advertisementBonusPointsValue)
    {
        $this->advertisementBonusPointsValue = $advertisementBonusPointsValue;

        return $this;
    }

    /**
     * Get advertisementBonusPointsValue
     *
     * @return integer
     */
    public function getAdvertisementBonusPointsValue()
    {
        return $this->advertisementBonusPointsValue;
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
     * Set advertisementStore
     *
     * @param \ModelBundle\Entity\Store $advertisementStore
     *
     * @return Advertisement
     */
    public function setAdvertisementStore(\ModelBundle\Entity\Store $advertisementStore = null)
    {
        $this->advertisementStore = $advertisementStore;

        return $this;
    }

    /**
     * Get advertisementStore
     *
     * @return \ModelBundle\Entity\Store
     */
    public function getAdvertisementStore()
    {
        return $this->advertisementStore;
    }

    /**
     * Add advertisementCategory
     *
     * @param \ModelBundle\Entity\Category $advertisementCategory
     *
     * @return Advertisement
     */
    public function addAdvertisementCategory(\ModelBundle\Entity\Category $advertisementCategory)
    {
        $this->advertisementCategories[] = $advertisementCategory;

        return $this;
    }

    /**
     * Remove advertisementCategory
     *
     * @param \ModelBundle\Entity\Category $advertisementCategory
     */
    public function removeAdvertisementCategory(\ModelBundle\Entity\Category $advertisementCategory)
    {
        $this->advertisementCategories->removeElement($advertisementCategory);
    }

    /**
     * Get advertisementCategories
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAdvertisementCategories()
    {
        return $this->advertisementCategories;
    }
}
