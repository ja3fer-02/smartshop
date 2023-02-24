<?php

namespace ModelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;

/**
 * FlashSale
 *
 * @ORM\Table(name="flash_sale")
 * @ORM\Entity(repositoryClass="ModelBundle\Repository\FlashSaleRepository")
 */
class FlashSale extends Promotion
{
    

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="flashSaleStartDate", type="datetime")
     * @Groups({"favoriteCustomerGroup"})
     */
    private $flashSaleStartDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="flashSaleEndDate", type="datetime")
     * @Groups({"favoriteCustomerGroup"})
     */
    private $flashSaleEndDate;


    
    /**
     * Set flashSaleStartDate
     *
     * @param \DateTime $flashSaleStartDate
     *
     * @return FlashSale
     */
    public function setFlashSaleStartDate($flashSaleStartDate)
    {
        $this->flashSaleStartDate = $flashSaleStartDate;

        return $this;
    }

    /**
     * Get flashSaleStartDate
     *
     * @return \DateTime
     */
    public function getFlashSaleStartDate()
    {
        return $this->flashSaleStartDate;
    }

    /**
     * Set flashSaleEndDate
     *
     * @param \DateTime $flashSaleEndDate
     *
     * @return FlashSale
     */
    public function setFlashSaleEndDate($flashSaleEndDate)
    {
        $this->flashSaleEndDate = $flashSaleEndDate;

        return $this;
    }

    /**
     * Get flashSaleEndDate
     *
     * @return \DateTime
     */
    public function getFlashSaleEndDate()
    {
        return $this->flashSaleEndDate;
    }
}
