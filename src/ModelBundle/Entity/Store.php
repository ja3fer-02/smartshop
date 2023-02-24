<?php

namespace ModelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups;

/**
 * Store
 *
 * @ORM\Table(name="store")
 * @ORM\Entity(repositoryClass="ModelBundle\Repository\StoreRepository")
 * @ORM\HasLifecycleCallbacks 
 * @ExclusionPolicy("all")
 */
class Store
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Expose
     * @Groups({"shortDataStoreGroup"})
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="ModelBundle\Entity\StoreChain", inversedBy="stores")
     */
    private $storeChain;

    /**
     * @var string
     *
     * @ORM\Column(name="storeName", type="string", length=255)
     * @Expose
     * @Groups({"shortDataStoreGroup"})
     */
    private $storeName;

    /**
     * @var string
     *
     * @ORM\Column(name="storeDescription", type="text")
     * @Expose
     * @Groups({"shortDataStoreGroup"})     
     */
    private $storeDescription;

    /**
     * @var string
     *
     * @ORM\Column(name="storeLogo", type="text")
     * @Expose
     * @Groups({"shortDataStoreGroup"})     
     */
    private $storeLogo;

    /**
     * @var string
     *
     * @ORM\Column(name="storeWebsite", type="text")
     * @Expose
     * @Groups({"shortDataStoreGroup"})     
     */
    private $storeWebsite;

    /**
     * @var string
     *
     * @ORM\Column(name="storeFacebookPageLink", type="text")
     * @Expose
     * @Groups({"shortDataStoreGroup"})     
     */
    private $storeFacebookPageLink;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="storeOpeningTime", type="datetime", nullable=true)
     * @Expose
     * @Groups({"shortDataStoreGroup"})     
     */
    private $storeOpeningTime;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="storeClosingTime", type="datetime", nullable=true)
     * @Expose
     * @Groups({"shortDataStoreGroup"})     
     */
    private $storeClosingTime;


    /**
     * One Store has Many Advertisement.
     * @ORM\OneToMany(targetEntity="Advertisement", mappedBy="advertisementStore")  
     */
    private $storeAdvertisements;

    /**
     * One Store has One Location.
     * @ORM\OneToOne(targetEntity="Location", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="location_id", referencedColumnName="id")
     * @Expose
     * @Groups({"shortDataStoreGroup"})     
     */
    private $location;

    /**
     * One Store has Many StoreProduct.
     * @ORM\OneToMany(targetEntity="StoreProduct", mappedBy="storeProductStore")
     * @Expose
     * @Groups({"shortDataStoreGroup"})  
     */
    private $storeProductsStore;


     /**
     * Many Stores are managed by One StoreManager.
     * @ORM\ManyToOne(targetEntity="StoreManager", inversedBy="managedStores")
     * @ORM\JoinColumn(name="store_manager_id", referencedColumnName="id")
     */
    private $storeManager;

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
        $this->storeAdvertisements = new \Doctrine\Common\Collections\ArrayCollection();
        $this->storeProductsStore = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set storeName
     *
     * @param string $storeName
     *
     * @return Store
     */
    public function setStoreName($storeName)
    {
        $this->storeName = $storeName;

        return $this;
    }

    /**
     * Get storeName
     *
     * @return string
     */
    public function getStoreName()
    {
        return $this->storeName;
    }

    /**
     * Set storeDescription
     *
     * @param string $storeDescription
     *
     * @return Store
     */
    public function setStoreDescription($storeDescription)
    {
        $this->storeDescription = $storeDescription;

        return $this;
    }

    /**
     * Get storeDescription
     *
     * @return string
     */
    public function getStoreDescription()
    {
        return $this->storeDescription;
    }

    /**
     * Set storeLogo
     *
     * @param string $storeLogo
     *
     * @return Store
     */
    public function setStoreLogo($storeLogo)
    {
        $this->storeLogo = $storeLogo;

        return $this;
    }

    /**
     * Get storeLogo
     *
     * @return string
     */
    public function getStoreLogo()
    {
        return $this->storeLogo;
    }

    /**
     * Set storeWebsite
     *
     * @param string $storeWebsite
     *
     * @return Store
     */
    public function setStoreWebsite($storeWebsite)
    {
        $this->storeWebsite = $storeWebsite;

        return $this;
    }

    /**
     * Get storeWebsite
     *
     * @return string
     */
    public function getStoreWebsite()
    {
        return $this->storeWebsite;
    }

    /**
     * Set storeFacebookPageLink
     *
     * @param string $storeFacebookPageLink
     *
     * @return Store
     */
    public function setStoreFacebookPageLink($storeFacebookPageLink)
    {
        $this->storeFacebookPageLink = $storeFacebookPageLink;

        return $this;
    }

    /**
     * Get storeFacebookPageLink
     *
     * @return string
     */
    public function getStoreFacebookPageLink()
    {
        return $this->storeFacebookPageLink;
    }

    /**
     * Set storeOpeningTime
     *
     * @param \DateTime $storeOpeningTime
     *
     * @return Store
     */
    public function setStoreOpeningTime($storeOpeningTime)
    {
        $this->storeOpeningTime = $storeOpeningTime;

        return $this;
    }

    /**
     * Get storeOpeningTime
     *
     * @return \DateTime
     */
    public function getStoreOpeningTime()
    {
        return $this->storeOpeningTime;
    }

    /**
     * Set storeClosingTime
     *
     * @param \DateTime $storeClosingTime
     *
     * @return Store
     */
    public function setStoreClosingTime($storeClosingTime)
    {
        $this->storeClosingTime = $storeClosingTime;

        return $this;
    }

    /**
     * Get storeClosingTime
     *
     * @return \DateTime
     */
    public function getStoreClosingTime()
    {
        return $this->storeClosingTime;
    }

    /**
     * Set storeChain
     *
     * @param \ModelBundle\Entity\StoreChain $storeChain
     *
     * @return Store
     */
    public function setStoreChain(\ModelBundle\Entity\StoreChain $storeChain = null)
    {
        $this->storeChain = $storeChain;

        return $this;
    }

    /**
     * Get storeChain
     *
     * @return \ModelBundle\Entity\StoreChain
     */
    public function getStoreChain()
    {
        return $this->storeChain;
    }

    /**
     * Add storeAdvertisement
     *
     * @param \ModelBundle\Entity\Advertisement $storeAdvertisement
     *
     * @return Store
     */
    public function addStoreAdvertisement(\ModelBundle\Entity\Advertisement $storeAdvertisement)
    {
        $this->storeAdvertisements[] = $storeAdvertisement;

        return $this;
    }

    /**
     * Remove storeAdvertisement
     *
     * @param \ModelBundle\Entity\Advertisement $storeAdvertisement
     */
    public function removeStoreAdvertisement(\ModelBundle\Entity\Advertisement $storeAdvertisement)
    {
        $this->storeAdvertisements->removeElement($storeAdvertisement);
    }

    /**
     * Get storeAdvertisements
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getStoreAdvertisements()
    {
        return $this->storeAdvertisements;
    }

    /**
     * Set location
     *
     * @param \ModelBundle\Entity\Location $location
     *
     * @return Store
     */
    public function setLocation(\ModelBundle\Entity\Location $location = null)
    {
        $this->location = $location;

        return $this;
    }

    /**
     * Get location
     *
     * @return \ModelBundle\Entity\Location
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Add storeProductsStore
     *
     * @param \ModelBundle\Entity\StoreProduct $storeProductsStore
     *
     * @return Store
     */
    public function addStoreProductsStore(\ModelBundle\Entity\StoreProduct $storeProductsStore)
    {
        $this->storeProductsStore[] = $storeProductsStore;

        return $this;
    }

    /**
     * Remove storeProductsStore
     *
     * @param \ModelBundle\Entity\StoreProduct $storeProductsStore
     */
    public function removeStoreProductsStore(\ModelBundle\Entity\StoreProduct $storeProductsStore)
    {
        $this->storeProductsStore->removeElement($storeProductsStore);
    }

    /**
     * Get storeProductsStore
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getStoreProductsStore()
    {
        return $this->storeProductsStore;
    }

    /**
     * Set storeManager
     *
     * @param \ModelBundle\Entity\StoreManager $storeManager
     *
     * @return Store
     */
    public function setStoreManager(\ModelBundle\Entity\StoreManager $storeManager = null)
    {
        $this->storeManager = $storeManager;

        return $this;
    }

    /**
     * Get storeManager
     *
     * @return \ModelBundle\Entity\StoreManager
     */
    public function getStoreManager()
    {
        return $this->storeManager;
    }
}
