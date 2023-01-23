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
use Ecomm\Subscription\Model\ResourceModel\SubscriptionCron as ResourseSubscription;
use Ecomm\Subscription\Api\Data\SubscriptionCronInterface;

/**
 * Description SubscriptionCron Table AbstractModel
 */
class SubscriptionCron extends AbstractModel implements SubscriptionCronInterface
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
        return $this->getData(SubscriptionCronInterface::CUSTOMER_ID);
    }

    /**
     * Set CustomerId
     *
     * @param int $customerId
     * @return string
     */
    public function setCustomerId($customerId)
    {
        return $this->setData(SubscriptionCronInterface::CUSTOMER_ID, $customerId);
    }
    
    /**
     * Get ProductId
     *
     * @return mixed
     */
    public function getProductId()
    {
        return $this->getData(SubscriptionCronInterface::PRODUCT_ID);
    }

    /**
     * Set ProductId
     *
     * @param int $productId
     * @return string
     */
    public function setProductId($productId)
    {
        return $this->setData(SubscriptionCronInterface::PRODUCT_ID, $productId);
    }
    
    /**
     * Get CreatedAt
     *
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->getData(SubscriptionCronInterface::CREATED_AT);
    }

    /**
     * Set CreatedAt
     *
     * @param int $createdAt
     * @return string
     */
    public function setCreatedAt($createdAt)
    {
        return $this->setData(SubscriptionCronInterface::CREATED_AT, $createdAt);
    }

    /**
     * Get UpdatedAt
     *
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->getData(SubscriptionCronInterface::UPDATED_AT);
    }

    /**
     * Set Video Status
     *
     * @param int $updatedAt
     * @return string
     */
    public function setUpdatedAt($updatedAt)
    {
        return $this->setData(SubscriptionCronInterface::UPDATED_AT, $updatedAt);
    }

    /**
     * Get Subscription Type
     *
     * @return mixed
     */
    public function getSubscriptionType()
    {
        return $this->getData(SubscriptionCronInterface::SUBSCRIPTION_TYPE);
    }

    /**
     * Set Video Status
     *
     * @param int $subscriptionType
     * @return string
     */
    public function setSubscriptionType($subscriptionType)
    {
        return $this->setData(SubscriptionCronInterface::SUBSCRIPTION_TYPE, $subscriptionType);
    }

    /**
     * Get Subscription Type
     *
     * @return mixed
     */
    public function getSubscriptionStartDate()
    {
        return $this->getData(SubscriptionCronInterface::SUBSCRIPTION_START_DATE);
    }

    /**
     * Set Video Status
     *
     * @param int $subscriptionStartDate
     * @return string
     */
    public function setSubscriptionStartDate($subscriptionStartDate)
    {
        return $this->setData(SubscriptionCronInterface::SUBSCRIPTION_START_DATE, $subscriptionStartDate);
    }

    /**
     * Get Dicount Type
     *
     * @return mixed
     */
    public function getDicountType()
    {
        return $this->getData(SubscriptionCronInterface::DISCOUNT_TYPE);
    }

    /**
     * Set Video Status
     *
     * @param int $dicountType
     * @return string
     */
    public function setDicountType($dicountType)
    {
        return $this->setData(SubscriptionCronInterface::DISCOUNT_TYPE, $dicountType);
    }

    /**
     * Get Dicount Value
     *
     * @return mixed
     */
    public function getDicountValue()
    {
        return $this->getData(SubscriptionCronInterface::DISCOUNT_VALUE);
    }

    /**
     * Set Dicount Value
     *
     * @param int $dicountValue
     * @return string
     */
    public function setDicountValue($dicountValue)
    {
        return $this->setData(SubscriptionCronInterface::DISCOUNT_VALUE, $dicountValue);
    }

    /**
     * Get Subscription End Date
     *
     * @return mixed
     */
    public function getSubscriptionEndType()
    {
        return $this->getData(SubscriptionCronInterface::SUBSCRIPTION_END_TYPE);
    }

    /**
     * Set Subscription End Date
     *
     * @param int $subscriptionEndType
     * @return string
     */
    public function setSubscriptionEndType($subscriptionEndType)
    {
        return $this->setData(SubscriptionCronInterface::SUBSCRIPTION_END_TYPE, $subscriptionEndType);
    }

    /**
     * Get Subscription End Date
     *
     * @return mixed
     */
    public function getSubscriptionEndValue()
    {
        return $this->getData(SubscriptionCronInterface::SUBSCRIPTION_END_VALUE);
    }

    /**
     * Set Subscription End Date
     *
     * @param int $subscriptionEndValue
     * @return string
     */
    public function setSubscriptionEndValue($subscriptionEndValue)
    {
        return $this->setData(SubscriptionCronInterface::SUBSCRIPTION_END_VALUE, $subscriptionEndValue);
    }

    /**
     * Get Next Date
     *
     * @return mixed
     */
    public function getNextDate()
    {
        return $this->getData(SubscriptionCronInterface::NEXT_DATE);
    }

    /**
     * Set Next Date
     *
     * @param int $nextDate
     * @return string
     */
    public function setNextDate($nextDate)
    {
        return $this->setData(SubscriptionCronInterface::NEXT_DATE, $nextDate);
    }

    /**
     * Get Last Action Date
     *
     * @return mixed
     */
    public function getLastActionDate()
    {
        return $this->getData(SubscriptionCronInterface::LAST_ACTION_DATE);
    }

    /**
     * Set Last Action Date
     *
     * @param int $lastActionDate
     * @return string
     */
    public function setLastActionDate($lastActionDate)
    {
        return $this->setData(SubscriptionCronInterface::LAST_ACTION_DATE, $lastActionDate);
    }

    /**
     * Get Last Action Status
     *
     * @return mixed
     */
    public function getLastActionStatus()
    {
        return $this->getData(SubscriptionCronInterface::LAST_ACTION_STATUS);
    }

    /**
     * Set Last Action Status
     *
     * @param int $lastActionStatus
     * @return string
     */
    public function setLastActionStatus($lastActionStatus)
    {
        return $this->setData(SubscriptionCronInterface::LAST_ACTION_STATUS, $lastActionStatus);
    }

    /**
     * Get Status
     *
     * @return mixed
     */
    public function getStatus()
    {
        return $this->getData(SubscriptionCronInterface::STATUS);
    }

    /**
     * Set Status
     *
     * @param int $status
     * @return string
     */
    public function setStatus($status)
    {
        return $this->setData(SubscriptionCronInterface::STATUS, $status);
    }
}
