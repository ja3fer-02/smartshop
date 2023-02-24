<?php

namespace ModelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups;

/**
 * Post
 *
 * @ORM\Table(name="post")
 * @ORM\Entity(repositoryClass="ModelBundle\Repository\PostRepository")
 * @ORM\HasLifecycleCallbacks 
 * @ExclusionPolicy("all")
 */
class Post
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Expose
     * @Groups({"myPostGroup","othersPostGroup"})
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="postText", type="string", length=255)
     * @Expose
     * @Groups({"myPostGroup","othersPostGroup"})
     */
    private $postText;

    /**
     * @var string
     *
     * @ORM\Column(name="postImage", type="string", length=255, nullable=true)
     * @Expose
     * @Groups({"myPostGroup","othersPostGroup"})
     */
    private $postImage;

    /**
     * One Post has Many Comments.
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="commentPost", cascade={"persist", "remove"})
     * @Expose
     * @Groups({"myPostGroup","othersPostGroup"})
     */
    private $postComments;
    /**
     * Many Posts have One Promotion.
     * @ORM\ManyToOne(targetEntity="Promotion")
     * @ORM\JoinColumn(name="promotion_id", referencedColumnName="id")
     */
    private $postPromotion;

     /**
     * Many Posts can be liked from Many Customers.
     * @ORM\ManyToMany(targetEntity="Customer", mappedBy="postCustomersLiked")
     */
    private $customerPostsLiked;

    /**
     * Many Posts can be shared from One Customer.
     * @ORM\ManyToOne(targetEntity="Customer", inversedBy="customerPostsShared")
     * @ORM\JoinColumn(name="customer_id", referencedColumnName="id")
     * @Expose
     * @Groups({"othersPostGroup"})
     */
    private $postSharedCustomer;

    /**
     * @ORM\Column(type="datetime")
     * @Expose
     * @Groups({"myPostGroup","othersPostGroup"})
     */
    private $createdAt;

    /**
    * @ORM\Column(type="datetime")
    */
    private $updatedAt;


     /**
     * @var float
     *
     * @ORM\Column(name="longitude", type="float", nullable=true)
     * @Expose
     * @Groups({"othersPostGroup"})
     */
    private $longitude;

    /**
     * @var float
     *
     * @ORM\Column(name="latitude", type="float", nullable=true)
     * @Expose
     * @Groups({"othersPostGroup"})
     */
    private $latitude;

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
        $this->postComments = new \Doctrine\Common\Collections\ArrayCollection();
        $this->customerPostsLiked = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set postText
     *
     * @param string $postText
     *
     * @return Post
     */
    public function setPostText($postText)
    {
        $this->postText = $postText;

        return $this;
    }

    /**
     * Get postText
     *
     * @return string
     */
    public function getPostText()
    {
        return $this->postText;
    }

    /**
     * Set postImage
     *
     * @param string $postImage
     *
     * @return Post
     */
    public function setPostImage($postImage)
    {
        $this->postImage = $postImage;

        return $this;
    }

    /**
     * Get postImage
     *
     * @return string
     */
    public function getPostImage()
    {
        return $this->postImage;
    }

    /**
     * Add postComment
     *
     * @param \ModelBundle\Entity\Comment $postComment
     *
     * @return Post
     */
    public function addPostComment(\ModelBundle\Entity\Comment $postComment)
    {
        $this->postComments[] = $postComment;

        return $this;
    }

    /**
     * Remove postComment
     *
     * @param \ModelBundle\Entity\Comment $postComment
     */
    public function removePostComment(\ModelBundle\Entity\Comment $postComment)
    {
        $this->postComments->removeElement($postComment);
    }

    /**
     * Get postComments
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPostComments()
    {
        return $this->postComments;
    }

    /**
     * Set postPromotion
     *
     * @param \ModelBundle\Entity\Promotion $postPromotion
     *
     * @return Post
     */
    public function setPostPromotion(\ModelBundle\Entity\Promotion $postPromotion = null)
    {
        $this->postPromotion = $postPromotion;

        return $this;
    }

    /**
     * Get postPromotion
     *
     * @return \ModelBundle\Entity\Promotion
     */
    public function getPostPromotion()
    {
        return $this->postPromotion;
    }

    /**
     * Add customerPostsLiked
     *
     * @param \ModelBundle\Entity\Customer $customerPostsLiked
     *
     * @return Post
     */
    public function addCustomerPostsLiked(\ModelBundle\Entity\Customer $customerPostsLiked)
    {
        $this->customerPostsLiked[] = $customerPostsLiked;

        return $this;
    }

    /**
     * Remove customerPostsLiked
     *
     * @param \ModelBundle\Entity\Customer $customerPostsLiked
     */
    public function removeCustomerPostsLiked(\ModelBundle\Entity\Customer $customerPostsLiked)
    {
        $this->customerPostsLiked->removeElement($customerPostsLiked);
    }

    /**
     * Get customerPostsLiked
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCustomerPostsLiked()
    {
        return $this->customerPostsLiked;
    }

    /**
     * Set postSharedCustomer
     *
     * @param \ModelBundle\Entity\Customer $postSharedCustomer
     *
     * @return Post
     */
    public function setPostSharedCustomer(\ModelBundle\Entity\Customer $postSharedCustomer = null)
    {
        $this->postSharedCustomer = $postSharedCustomer;

        return $this;
    }

    /**
     * Get postSharedCustomer
     *
     * @return \ModelBundle\Entity\Customer
     */
    public function getPostSharedCustomer()
    {
        return $this->postSharedCustomer;
    }

    /**
     * Set longitude
     *
     * @param float $longitude
     *
     * @return Location
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return float
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set latitude
     *
     * @param float $latitude
     *
     * @return Location
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude
     *
     * @return float
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

}
