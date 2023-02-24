<?php

namespace ModelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Bonus
 *
 * @ORM\Table(name="bonus")
 * @ORM\Entity(repositoryClass="ModelBundle\Repository\BonusRepository")
 * @ORM\HasLifecycleCallbacks 
 */
class Bonus
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
     * @var float
     *
     * @ORM\Column(name="bonusAmount", type="float")
     */
    private $bonusAmount;

    /**
     * @var string
     *
     * @ORM\Column(name="bonusTitle", type="string", length=255)
     */
    private $bonusTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="bonusDescription", type="string", length=255)
     */
    private $bonusDescription;

    /**
     * @var int
     *
     * @ORM\Column(name="bonusPointValue", type="integer")
     */
    private $bonusPointValue;


    /**
     * One Bonus has Many UserBonuses.
     * @ORM\OneToMany(targetEntity="UserBonus", mappedBy="UserBonusBonus")
     */
    private $BonusUserBonuses;

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
        $this->BonusUserBonuses = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set bonusAmount
     *
     * @param float $bonusAmount
     *
     * @return Bonus
     */
    public function setBonusAmount($bonusAmount)
    {
        $this->bonusAmount = $bonusAmount;

        return $this;
    }

    /**
     * Get bonusAmount
     *
     * @return float
     */
    public function getBonusAmount()
    {
        return $this->bonusAmount;
    }

    /**
     * Set bonusTitle
     *
     * @param string $bonusTitle
     *
     * @return Bonus
     */
    public function setBonusTitle($bonusTitle)
    {
        $this->bonusTitle = $bonusTitle;

        return $this;
    }

    /**
     * Get bonusTitle
     *
     * @return string
     */
    public function getBonusTitle()
    {
        return $this->bonusTitle;
    }

    /**
     * Set bonusDescription
     *
     * @param string $bonusDescription
     *
     * @return Bonus
     */
    public function setBonusDescription($bonusDescription)
    {
        $this->bonusDescription = $bonusDescription;

        return $this;
    }

    /**
     * Get bonusDescription
     *
     * @return string
     */
    public function getBonusDescription()
    {
        return $this->bonusDescription;
    }

    /**
     * Set bonusPointValue
     *
     * @param integer $bonusPointValue
     *
     * @return Bonus
     */
    public function setBonusPointValue($bonusPointValue)
    {
        $this->bonusPointValue = $bonusPointValue;

        return $this;
    }

    /**
     * Get bonusPointValue
     *
     * @return integer
     */
    public function getBonusPointValue()
    {
        return $this->bonusPointValue;
    }

    /**
     * Add bonusUserBonus
     *
     * @param \ModelBundle\Entity\UserBonus $bonusUserBonus
     *
     * @return Bonus
     */
    public function addBonusUserBonus(\ModelBundle\Entity\UserBonus $bonusUserBonus)
    {
        $this->BonusUserBonuses[] = $bonusUserBonus;

        return $this;
    }

    /**
     * Remove bonusUserBonus
     *
     * @param \ModelBundle\Entity\UserBonus $bonusUserBonus
     */
    public function removeBonusUserBonus(\ModelBundle\Entity\UserBonus $bonusUserBonus)
    {
        $this->BonusUserBonuses->removeElement($bonusUserBonus);
    }

    /**
     * Get bonusUserBonuses
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBonusUserBonuses()
    {
        return $this->BonusUserBonuses;
    }
}
