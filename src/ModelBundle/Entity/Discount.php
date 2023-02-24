<?php

namespace ModelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups;

/**
 * Discount
 *
 * @ORM\Table(name="discount")
 * @ORM\Entity(repositoryClass="ModelBundle\Repository\DiscountRepository")
 */
class Discount extends Promotion
{
    /**
     * @var int
     *
     * @ORM\Column(name="discountPercentage", type="integer", nullable=true)
     * @Groups({"favoriteCustomerGroup"})
     */
    private $discountPercentage;


    /**
     * Set discountPercentage
     *
     * @param integer $discountPercentage
     *
     * @return Discount
     */
    public function setDiscountPercentage($discountPercentage)
    {
        $this->discountPercentage = $discountPercentage;

        return $this;
    }

    /**
     * Get discountPercentage
     *
     * @return integer
     */
    public function getDiscountPercentage()
    {
        return $this->discountPercentage;
    }
}
