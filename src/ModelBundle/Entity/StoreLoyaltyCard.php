<?php

namespace ModelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups;

/**
 * StoreLoyaltyCard
 *
 * @ORM\Table(name="store_loyalty_card")
 * @ORM\Entity(repositoryClass="ModelBundle\Repository\StoreLoyaltyCardRepository")
 * @ORM\HasLifecycleCallbacks 
 * @ExclusionPolicy("all")
 */
class StoreLoyaltyCard
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @Expose
     * @Groups({"shortDataUserLoyaltyCardGroup","userLoyaltyCardWithStoreChainGroup"})
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="loyaltyCardImage", type="string", length=255)
     * @Expose
     * @Groups({"shortDataUserLoyaltyCardGroup","userLoyaltyCardWithStoreChainGroup"})
     */
    private $loyaltyCardImage;

    /**
     * @var string
     *
     * @ORM\Column(name="loyaltyCardName", type="string", length=255)
     * @Expose
     * @Groups({"shortDataUserLoyaltyCardGroup","userLoyaltyCardWithStoreChainGroup"})
     */
    private $loyaltyCardName;

    /**
     * Many StoreLoyaltyCards have One StoreChain.
     * @ORM\ManyToOne(targetEntity="StoreChain", inversedBy="storeChainStoreLoyaltyCards")
     * @Expose
     * @Groups({"userLoyaltyCardWithStoreChainGroup"})
     * @ORM\JoinColumn(name="store_chain_id", referencedColumnName="id")
     */
    private $loyaltyCardStoreChain;

    /**
     * One StoreLoyaltyCard has Many UserLoyaltyCard.
     * @ORM\OneToMany(targetEntity="UserLoyaltyCard", mappedBy="storeLoyaltyCard")
     */
    private $userLoyaltyCards;
    

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
        $this->userLoyaltyCards = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set loyaltyCardImage
     *
     * @param string $loyaltyCardImage
     *
     * @return StoreLoyaltyCard
     */
    public function setLoyaltyCardImage($loyaltyCardImage)
    {
        $this->loyaltyCardImage = $loyaltyCardImage;

        return $this;
    }

    /**
     * Get loyaltyCardImage
     *
     * @return string
     */
    public function getLoyaltyCardImage()
    {
        return $this->loyaltyCardImage;
    }

    /**
     * Set loyaltyCardName
     *
     * @param string $loyaltyCardName
     *
     * @return StoreLoyaltyCard
     */
    public function setLoyaltyCardName($loyaltyCardName)
    {
        $this->loyaltyCardName = $loyaltyCardName;

        return $this;
    }

    /**
     * Get loyaltyCardName
     *
     * @return string
     */
    public function getLoyaltyCardName()
    {
        return $this->loyaltyCardName;
    }

    /**
     * Set loyaltyCardStoreChain
     *
     * @param \ModelBundle\Entity\StoreChain $loyaltyCardStoreChain
     *
     * @return StoreLoyaltyCard
     */
    public function setLoyaltyCardStoreChain(\ModelBundle\Entity\StoreChain $loyaltyCardStoreChain = null)
    {
        $this->loyaltyCardStoreChain = $loyaltyCardStoreChain;

        return $this;
    }

    /**
     * Get loyaltyCardStoreChain
     *
     * @return \ModelBundle\Entity\StoreChain
     */
    public function getLoyaltyCardStoreChain()
    {
        return $this->loyaltyCardStoreChain;
    }

    /**
     * Add userLoyaltyCard
     *
     * @param \ModelBundle\Entity\userLoyaltyCard $userLoyaltyCard
     *
     * @return StoreLoyaltyCard
     */
    public function addUserLoyaltyCard(\ModelBundle\Entity\userLoyaltyCard $userLoyaltyCard)
    {
        $this->userLoyaltyCards[] = $userLoyaltyCard;

        return $this;
    }

    /**
     * Remove userLoyaltyCard
     *
     * @param \ModelBundle\Entity\userLoyaltyCard $userLoyaltyCard
     */
    public function removeUserLoyaltyCard(\ModelBundle\Entity\userLoyaltyCard $userLoyaltyCard)
    {
        $this->userLoyaltyCards->removeElement($userLoyaltyCard);
    }

    /**
     * Get userLoyaltyCards
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUserLoyaltyCards()
    {
        return $this->userLoyaltyCards;
    }
}
