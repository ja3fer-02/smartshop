<?php

namespace ModelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups;

/**
 * Comment
 *
 * @ORM\Table(name="comment")
 * @ORM\Entity(repositoryClass="ModelBundle\Repository\CommentRepository")
 * @ORM\HasLifecycleCallbacks 
 * @ExclusionPolicy("all")
 */
class Comment
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Expose
     * @Groups({"myPostGroup","othersPostGroup","commentGroup"})
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="commentText", type="string", length=255)
     * @Expose
     * @Groups({"commentGroup"})
     */
    private $commentText;

    /**
     * Many Comments have One Post.
     * @ORM\ManyToOne(targetEntity="Post", inversedBy="postComments")
     * @ORM\JoinColumn(name="post_id", referencedColumnName="id")
     */
    private $commentPost;

    /**
     * Many Comments can be shared from One Customer.
     * @ORM\ManyToOne(targetEntity="Customer", inversedBy="customerComments")
     * @ORM\JoinColumn(name="customer_id", referencedColumnName="id")
     * @Expose
     * @Groups({"commentGroup"})
     */
    private $commentCustomer;
     /**
     * @ORM\Column(type="datetime")
     * @Expose
     * @Groups({"commentGroup"})
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
     * Set commentText
     *
     * @param string $commentText
     *
     * @return Comment
     */
    public function setCommentText($commentText)
    {
        $this->commentText = $commentText;

        return $this;
    }

    /**
     * Get commentText
     *
     * @return string
     */
    public function getCommentText()
    {
        return $this->commentText;
    }

    /**
     * Set commentPost
     *
     * @param \ModelBundle\Entity\Post $commentPost
     *
     * @return Comment
     */
    public function setCommentPost(\ModelBundle\Entity\Post $commentPost = null)
    {
        $this->commentPost = $commentPost;

        return $this;
    }

    /**
     * Get commentPost
     *
     * @return \ModelBundle\Entity\Post
     */
    public function getCommentPost()
    {
        return $this->commentPost;
    }

    /**
     * Set commentCustomer
     *
     * @param \ModelBundle\Entity\Customer $commentCustomer
     *
     * @return Comment
     */
    public function setCommentCustomer(\ModelBundle\Entity\Customer $commentCustomer = null)
    {
        $this->commentCustomer = $commentCustomer;

        return $this;
    }

    /**
     * Get commentCustomer
     *
     * @return \ModelBundle\Entity\Customer
     */
    public function getCommentCustomer()
    {
        return $this->commentCustomer;
    }
}
