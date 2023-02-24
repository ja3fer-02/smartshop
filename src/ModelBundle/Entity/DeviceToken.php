<?php

namespace ModelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DeviceToken
 *
 * @ORM\Table(name="device_token")
 * @ORM\Entity(repositoryClass="ModelBundle\Repository\DeviceTokenRepository")
 */
class DeviceToken
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
     * @ORM\Column(name="notificationDeviceToken", type="string", length=255)
     */
    private $notificationDeviceToken;

    /**
     * Many DeviceToken have One Customer.
     * @ORM\ManyToOne(targetEntity="Customer", inversedBy="customerDeviceToken")
     * @ORM\JoinColumn(name="customer_id", referencedColumnName="id")
     */
     private $deviceTokenCustomer;


     /**
     * @var string
     *
     * @ORM\Column(name="device_os", type="string", length=255, nullable=false)
     */
     private $deviceOS;

     /**
     * @var string
     *
     * @ORM\Column(name="device_language", type="string", length=255, nullable=false)
     */
     private $deviceLanguage;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set notificationDeviceToken
     *
     * @param string $notificationDeviceToken
     *
     * @return DeviceToken
     */
    public function setNotificationDeviceToken($notificationDeviceToken)
    {
        $this->notificationDeviceToken = $notificationDeviceToken;

        return $this;
    }

    /**
     * Get notificationDeviceToken
     *
     * @return string
     */
    public function getNotificationDeviceToken()
    {
        return $this->notificationDeviceToken;
    }

    /**
     * Set deviceTokenCustomer
     *
     * @param \ModelBundle\Entity\Customer $deviceTokenCustomer
     *
     * @return DeviceToken
     */
    public function setDeviceTokenCustomer(\ModelBundle\Entity\Customer $deviceTokenCustomer = null)
    {
        $this->deviceTokenCustomer = $deviceTokenCustomer;

        return $this;
    }

    /**
     * Get deviceTokenCustomer
     *
     * @return \ModelBundle\Entity\Customer
     */
    public function getDeviceTokenCustomer()
    {
        return $this->deviceTokenCustomer;
    }

    /**
     * Set deviceOS
     *
     * @param string $deviceOS
     *
     * @return DeviceToken
     */
    public function setDeviceOS($deviceOS)
    {
        $this->deviceOS = $deviceOS;

        return $this;
    }

    /**
     * Get deviceOS
     *
     * @return string
     */
    public function getDeviceOS()
    {
        return $this->deviceOS;
    }

    /**
     * Set deviceLanguage
     *
     * @param string $deviceLanguage
     *
     * @return DeviceToken
     */
    public function setDeviceLanguage($deviceLanguage)
    {
        $this->deviceLanguage = $deviceLanguage;

        return $this;
    }

    /**
     * Get deviceLanguage
     *
     * @return string
     */
    public function getDeviceLanguage()
    {
        return $this->deviceLanguage;
    }

}

