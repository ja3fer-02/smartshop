<?php

namespace ModelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups;

/**
 * StoreChain
 *
 * @ORM\Table(name="store_chain")
 * @ORM\Entity(repositoryClass="ModelBundle\Repository\StoreChainRepository")
 * @ORM\HasLifecycleCallbacks 
* @ExclusionPolicy("all")
 */
class StoreChain
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Expose
     * @Groups({"userLoyaltyCardWithStoreChainGroup"})
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="ModelBundle\Entity\Store", mappedBy="storeChain")
     */
    private $stores;

    /**
     * @var string
     * @Expose
     * @ORM\Column(name="storeChainName", type="string", length=255)
     */
    private $storeChainName;

    /**
     * @var string
     * @Expose
     * @ORM\Column(name="storeChainDescription", type="text")
     */
    private $storeChainDescription;

    /**
     * @var string
     * @Expose
     * @ORM\Column(name="storeChainWebsite", type="text")
     */
    private $storeChainWebsite;

    /**
     * @var string
     * @Expose
     * @ORM\Column(name="storeChainAddress", type="text")
     */
    private $storeChainAddress;

    /**
     * @var string
     * @Expose
     * @ORM\Column(name="storeChainLogo", type="text")
     */
    private $storeChainLogo;
    
    /**
     * One StoreChain has Many StoreLoyaltyCard.
     * @ORM\OneToMany(targetEntity="StoreLoyaltyCard", mappedBy="loyaltyCardStoreChain")
     */
    private $storeChainStoreLoyaltyCards;

    /**
     * One StoreChain is managed by onr StoreChainManager.
     * @ORM\OneToOne(targetEntity="StoreChainManager", inversedBy="managedstoreChain")
     * @ORM\JoinColumn(name="store_chain_manager_id", referencedColumnName="id")
     */
    private $storeChainManager;

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
        $this->stores = new \Doctrine\Common\Collections\ArrayCollection();
        $this->storeChainStoreLoyaltyCards = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set storeChainName
     *
     * @param string $storeChainName
     *
     * @return StoreChain
     */
    public function setStoreChainName($storeChainName)
    {
        $this->storeChainName = $storeChainName;

        return $this;
    }

    /**
     * Get storeChainName
     *
     * @return string
     */
    public function getStoreChainName()
    {
        return $this->storeChainName;
    }

    /**
     * Set storeChainDescription
     *
     * @param string $storeChainDescription
     *
     * @return StoreChain
     */
    public function setStoreChainDescription($storeChainDescription)
    {
        $this->storeChainDescription = $storeChainDescription;

        return $this;
    }

    /**
     * Get storeChainDescription
     *
     * @return string
     */
    public function getStoreChainDescription()
    {
        return $this->storeChainDescription;
    }

    /**
     * Set storeChainWebsite
     *
     * @param string $storeChainWebsite
     *
     * @return StoreChain
     */
    public function setStoreChainWebsite($storeChainWebsite)
    {
        $this->storeChainWebsite = $storeChainWebsite;

        return $this;
    }

    /**
     * Get storeChainWebsite
     *
     * @return string
     */
    public function getStoreChainWebsite()
    {
        return $this->storeChainWebsite;
    }

    /**
     * Set storeChainAddress
     *
     * @param string $storeChainAddress
     *
     * @return StoreChain
     */
    public function setStoreChainAddress($storeChainAddress)
    {
        $this->storeChainAddress = $storeChainAddress;

        return $this;
    }

    /**
     * Get storeChainAddress
     *
     * @return string
     */
    public function getStoreChainAddress()
    {
        return $this->storeChainAddress;
    }

    /**
     * Set storeChainLogo
     *
     * @param string $storeChainLogo
     *
     * @return StoreChain
     */
    public function setStoreChainLogo($storeChainLogo)
    {
        $this->storeChainLogo = $storeChainLogo;

        return $this;
    }

    /**
     * Get storeChainLogo
     *
     * @return string
     */
    public function getStoreChainLogo()
    {
        return $this->storeChainLogo;
    }

    /**
     * Add store
     *
     * @param \ModelBundle\Entity\Store $store
     *
     * @return StoreChain
     */
    public function addStore(\ModelBundle\Entity\Store $store)
    {
        $this->stores[] = $store;

        return $this;
    }

    /**
     * Remove store
     *
     * @param \ModelBundle\Entity\Store $store
     */
    public function removeStore(\ModelBundle\Entity\Store $store)
    {
        $this->stores->removeElement($store);
    }

    /**
     * Get stores
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getStores()
    {
        return $this->stores;
    }

    /**
     * Add storeChainStoreLoyaltyCard
     *
     * @param \ModelBundle\Entity\StoreLoyaltyCard $storeChainStoreLoyaltyCard
     *
     * @return StoreChain
     */
    public function addStoreChainStoreLoyaltyCard(\ModelBundle\Entity\StoreLoyaltyCard $storeChainStoreLoyaltyCard)
    {
        $this->storeChainStoreLoyaltyCards[] = $storeChainStoreLoyaltyCard;

        return $this;
    }

    /**
     * Remove storeChainStoreLoyaltyCard
     *
     * @param \ModelBundle\Entity\StoreLoyaltyCard $storeChainStoreLoyaltyCard
     */
    public function removeStoreChainStoreLoyaltyCard(\ModelBundle\Entity\StoreLoyaltyCard $storeChainStoreLoyaltyCard)
    {
        $this->storeChainStoreLoyaltyCards->removeElement($storeChainStoreLoyaltyCard);
    }

    /**
     * Get storeChainStoreLoyaltyCards
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getStoreChainStoreLoyaltyCards()
    {
        return $this->storeChainStoreLoyaltyCards;
    }

    /**
     * Set storeChainManager
     *
     * @param \ModelBundle\Entity\StoreChainManager $storeChainManager
     *
     * @return StoreChain
     */
    public function setStoreChainManager(\ModelBundle\Entity\StoreChainManager $storeChainManager = null)
    {
        $this->storeChainManager = $storeChainManager;

        return $this;
    }

    /**
     * Get storeChainManager
     *
     * @return \ModelBundle\Entity\StoreChainManager
     */
    public function getStoreChainManager()
    {
        return $this->storeChainManager;
    }
}
