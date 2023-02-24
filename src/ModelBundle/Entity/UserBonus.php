<?php

namespace ModelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserBonus
 *
 * @ORM\Table(name="user_bonus")
 * @ORM\Entity(repositoryClass="ModelBundle\Repository\UserBonusRepository")
 * @ORM\HasLifecycleCallbacks 
 */
class UserBonus
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
     * @ORM\Column(name="userBonusBarCode", type="string", length=255)
     */
    private $userBonusBarCode;

    /**
     * @var bool
     *
     * @ORM\Column(name="userBonusConsumed", type="boolean")
     */
    private $userBonusConsumed;


     /**
     * Many UserBonuses have One Bonus.
     * @ORM\ManyToOne(targetEntity="Bonus", inversedBy="BonusUserBonuses")
     * @ORM\JoinColumn(name="Bonus_id", referencedColumnName="id")
     */
    private $UserBonusBonus;
    /**
     * Many UserBonus have One Customer.
     * @ORM\ManyToOne(targetEntity="Customer", inversedBy="customerUserBonus")
     * @ORM\JoinColumn(name="customer_id", referencedColumnName="id")
     */
     private $userBonusCustomer;

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
     * Set userBonusBarCode
     *
     * @param string $userBonusBarCode
     *
     * @return UserBonus
     */
    public function setUserBonusBarCode($userBonusBarCode)
    {
        $this->userBonusBarCode = $userBonusBarCode;

        return $this;
    }

    /**
     * Get userBonusBarCode
     *
     * @return string
     */
    public function getUserBonusBarCode()
    {
        return $this->userBonusBarCode;
    }

    /**
     * Set userBonusConsumed
     *
     * @param boolean $userBonusConsumed
     *
     * @return UserBonus
     */
    public function setUserBonusConsumed($userBonusConsumed)
    {
        $this->userBonusConsumed = $userBonusConsumed;

        return $this;
    }

    /**
     * Get userBonusConsumed
     *
     * @return boolean
     */
    public function getUserBonusConsumed()
    {
        return $this->userBonusConsumed;
    }

    /**
     * Set userBonusBonus
     *
     * @param \ModelBundle\Entity\Bonus $userBonusBonus
     *
     * @return UserBonus
     */
    public function setUserBonusBonus(\ModelBundle\Entity\Bonus $userBonusBonus = null)
    {
        $this->UserBonusBonus = $userBonusBonus;

        return $this;
    }

    /**
     * Get userBonusBonus
     *
     * @return \ModelBundle\Entity\Bonus
     */
    public function getUserBonusBonus()
    {
        return $this->UserBonusBonus;
    }

    /**
     * Set userBonusCustomer
     *
     * @param \ModelBundle\Entity\Customer $userBonusCustomer
     *
     * @return UserBonus
     */
    public function setUserBonusCustomer(\ModelBundle\Entity\Customer $userBonusCustomer = null)
    {
        $this->userBonusCustomer = $userBonusCustomer;

        return $this;
    }

    /**
     * Get userBonusCustomer
     *
     * @return \ModelBundle\Entity\Customer
     */
    public function getUserBonusCustomer()
    {
        return $this->userBonusCustomer;
    }
}
