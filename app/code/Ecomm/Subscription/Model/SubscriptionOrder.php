<?php

/**
 * PwC India
 *
 * @category Magento
 * @package Ecomm_Subscription
 * @author PwC India
 * @license GNU General Public License ("GPL") v3.0
 */
declare(strict_types=1);
namespace Ecomm\Subscription\Model;

use Magento\Framework\Model\AbstractModel;
use Ecomm\Subscription\Model\ResourceModel\SubscriptionOrder as ResourseSubscription;
use Ecomm\Subscription\Api\Data\SubscriptionOrderInterface;

/**
 * Description SubscriptionCron Table AbstractModel
 */
class SubscriptionOrder extends AbstractModel implements SubscriptionOrderInterface
{
    /**
     * Define resource model
     */
    protected function _construct()
    {
        $this->_init(ResourseSubscription::class);
    }

    /**
     * Get CustomerId
     *
     * @return mixed
     */
    public function getCustomerId()
    {
        return $this->getData(SubscriptionOrderInterface::CUSTOMER_ID);
    }

    /**
     * Set CustomerId
     *
     * @param int $customerId
     * @return string
     */
    public function setCustomerId($customerId)
    {
        return $this->setData(SubscriptionOrderInterface::CUSTOMER_ID, $customerId);
    }
    
    /**
     * Get ProductId
     *
     * @return mixed
     */
    public function getProductId()
    {
        return $this->getData(SubscriptionOrderInterface::PRODUCT_ID);
    }

    /**
     * Set ProductId
     *
     * @param int $productId
     * @return string
     */
    public function setProductId($productId)
    {
        return $this->setData(SubscriptionOrderInterface::PRODUCT_ID, $productId);
    }
    
    /**
     * Get Subscription Cron Id
     *
     * @return mixed
     */
    public function getSubscriptionCronId()
    {
        return $this->getData(SubscriptionOrderInterface::SUBSCRIPTION_CRON_ID);
    }

    /**
     * Set Subscription Cron Id
     *
     * @param int $subscriptionCronId
     * @return string
     */
    public function setSubscriptionCronId($subscriptionCronId)
    {
        return $this->setData(SubscriptionOrderInterface::SUBSCRIPTION_CRON_ID, $subscriptionCronId);
    }

    /**
     * Get Order Id
     *
     * @return mixed
     */
    public function getOrderId()
    {
        return $this->getData(SubscriptionOrderInterface::ORDER_ID);
    }

    /**
     * Set Order Id
     *
     * @param int $orderId
     * @return string
     */
    public function setOrderId($orderId)
    {
        return $this->setData(SubscriptionOrderInterface::ORDER_ID, $orderId);
    }

    /**
     * Get Dicount Type
     *
     * @return mixed
     */
    public function getDicountType()
    {
        return $this->getData(SubscriptionOrderInterface::DICOUNT_TYPE);
    }

    /**
     * Set Dicount Type
     *
     * @param int $dicountType
     * @return string
     */
    public function setDicountType($dicountType)
    {
        return $this->setData(SubscriptionOrderInterface::DICOUNT_TYPE, $dicountType);
    }

    /**
     * Get CreatedAt
     *
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->getData(SubscriptionOrderInterface::CREATED_AT);
    }

    /**
     * Set CreatedAt
     *
     * @param int $createdAt
     * @return string
     */
    public function setCreatedAt($createdAt)
    {
        return $this->setData(SubscriptionOrderInterface::CREATED_AT, $createdAt);
    }

    /**
     * Get UpdatedAt
     *
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->getData(SubscriptionOrderInterface::UPDATED_AT);
    }

    /**
     * Set UpdatedAt
     *
     * @param int $updatedAt
     * @return string
     */
    public function setUpdatedAt($updatedAt)
    {
        return $this->setData(SubscriptionOrderInterface::UPDATED_AT, $updatedAt);
    }
    /**
     * Get Status
     *
     * @return mixed
     */
    public function getStatus()
    {
        return $this->getData(SubscriptionOrderInterface::STATUS);
    }

    /**
     * Set Status
     *
     * @param int $status
     * @return string
     */
    public function setStatus($status)
    {
        return $this->setData(SubscriptionOrderInterface::STATUS, $status);
    }
}
