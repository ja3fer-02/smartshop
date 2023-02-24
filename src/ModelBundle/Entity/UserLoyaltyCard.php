<?php

namespace ModelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups;

/**
 * UserLoyaltyCard
 *
 * @ORM\Table(name="user_loyalty_card")
 * @ORM\Entity(repositoryClass="ModelBundle\Repository\UserLoyaltyCardRepository")
 * @ORM\HasLifecycleCallbacks 
 * @ExclusionPolicy("all")
 */
class UserLoyaltyCard
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Expose
     * @Groups({"shortDataUserLoyaltyCardGroup"})
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="barcode", type="string", length=255)
     * @Expose
     * @Groups({"shortDataUserLoyaltyCardGroup"})
     */
    private $barcode;

    /**
     * @var string
     *
     * @ORM\Column(name="barcode_format", type="string", length=255)
     * @Expose
     * @Groups({"shortDataUserLoyaltyCardGroup"})
     */
    private $barcodeFormat;

     /**
     * Many userLoyaltyCards have One Customer.
     * @ORM\ManyToOne(targetEntity="Customer", inversedBy="customerUserLoyaltyCards")
     * @ORM\JoinColumn(name="customer_id", referencedColumnName="id")
     */
    private $userLoyaltyCardCustomer;
 
     /**
     * Many UserLoyaltyCard have One StoreLoyaltyCard.
     * @ORM\ManyToOne(targetEntity="StoreLoyaltyCard", inversedBy="userLoyaltyCards")
     * @ORM\JoinColumn(name="store_loyalty_card_id", referencedColumnName="id")
     * @Expose
     * @Groups({"shortDataUserLoyaltyCardGroup"})
     */
    private $storeLoyaltyCard; 

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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
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

    /**
     * Set barcode
     *
     * @param string $barcodeFormat
     *
     * @return UserLoyaltyCard
     */
    public function setBarcodeFormat($barcodeFormat)
    {
        $this->barcodeFormat = $barcodeFormat;

        return $this;
    }

    /**
     * Get barcodeFormat
     *
     * @return string
     */
    public function getBarcodeFormat()
    {
        return $this->$barcodeFormat;
    }
    /**
     * Set userLoyaltyCardCustomer
     *
     * @param \ModelBundle\Entity\Customer $userLoyaltyCardCustomer
     *
     * @return UserLoyaltyCard
     */
    public function setUserLoyaltyCardCustomer(\ModelBundle\Entity\Customer $userLoyaltyCardCustomer = null)
    {
        $this->userLoyaltyCardCustomer = $userLoyaltyCardCustomer;

        return $this;
    }

    /**
     * Get userLoyaltyCardCustomer
     *
     * @return \ModelBundle\Entity\Customer
     */
    public function getUserLoyaltyCardCustomer()
    {
        return $this->userLoyaltyCardCustomer;
    }

    /**
     * Set storeLoyaltyCard
     *
     * @param \ModelBundle\Entity\StoreLoyaltyCard $storeLoyaltyCard
     *
     * @return UserLoyaltyCard
     */
    public function setStoreLoyaltyCard(\ModelBundle\Entity\StoreLoyaltyCard $storeLoyaltyCard = null)
    {
        $this->storeLoyaltyCard = $storeLoyaltyCard;

        return $this;
    }

    /**
     * Get storeLoyaltyCard
     *
     * @return \ModelBundle\Entity\StoreLoyaltyCard
     */
    public function getStoreLoyaltyCard()
    {
        return $this->storeLoyaltyCard;
    }
}
