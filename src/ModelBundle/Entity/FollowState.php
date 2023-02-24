<?php

namespace ModelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FollowState
 *
 * @ORM\Table(name="follow_state")
 * @ORM\Entity(repositoryClass="ModelBundle\Repository\FollowStateRepository")
 * @ORM\HasLifecycleCallbacks 
 */
class FollowState
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
     * @var bool
     *
     * @ORM\Column(name="accepted", type="boolean")
     */
    private $accepted;

    /**
     * @var bool
     *
     * @ORM\Column(name="blocked", type="boolean")
     */
    private $blocked;

    /**
     * Many FollowsState have One Customer.
     * @ORM\ManyToOne(targetEntity="Customer", inversedBy="customerFollowersState")
     * @ORM\JoinColumn(name="Customer_id", referencedColumnName="id")
     */
    private $followerStateCustomer;

    /**
     * Many FollowsState have One Customer.
     * @ORM\ManyToOne(targetEntity="Customer", inversedBy="customerFollowedsState")
     * @ORM\JoinColumn(name="Customer_id", referencedColumnName="id")
     */
    private $followedStateCustomer;
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
     * Set accepted
     *
     * @param boolean $accepted
     *
     * @return FollowState
     */
    public function setAccepted($accepted)
    {
        $this->accepted = $accepted;

        return $this;
    }

    /**
     * Get accepted
     *
     * @return boolean
     */
    public function getAccepted()
    {
        return $this->accepted;
    }

    /**
     * Set blocked
     *
     * @param boolean $blocked
     *
     * @return FollowState
     */
    public function setBlocked($blocked)
    {
        $this->blocked = $blocked;

        return $this;
    }

    /**
     * Get blocked
     *
     * @return boolean
     */
    public function getBlocked()
    {
        return $this->blocked;
    }

    /**
     * Set followerStateCustomer
     *
     * @param \ModelBundle\Entity\Customer $followerStateCustomer
     *
     * @return FollowState
     */
    public function setFollowerStateCustomer(\ModelBundle\Entity\Customer $followerStateCustomer = null)
    {
        $this->followerStateCustomer = $followerStateCustomer;

        return $this;
    }

    /**
     * Get followerStateCustomer
     *
     * @return \ModelBundle\Entity\Customer
     */
    public function getFollowerStateCustomer()
    {
        return $this->followerStateCustomer;
    }

    /**
     * Set followedStateCustomer
     *
     * @param \ModelBundle\Entity\Customer $followedStateCustomer
     *
     * @return FollowState
     */
    public function setFollowedStateCustomer(\ModelBundle\Entity\Customer $followedStateCustomer = null)
    {
        $this->followedStateCustomer = $followedStateCustomer;

        return $this;
    }

    /**
     * Get followedStateCustomer
     *
     * @return \ModelBundle\Entity\Customer
     */
    public function getFollowedStateCustomer()
    {
        return $this->followedStateCustomer;
    }
}
