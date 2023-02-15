<?php
/**
 * PwC India
 *
 * @category Magento
 * @package  Ecomm_VideoList
 * @author   PwC India
 * @license  GNU General Public License ("GPL") v3.0
 */

namespace Ecomm\Subscription\Api\Data;

interface SubscriptionCronInterface
{
    /**
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    public const ID                       = 'entity_id';
    public const CUSTOMER_ID              = 'customer_id';
    public const PRODUCT_ID               = 'product_id';
    public const CREATED_AT               = 'created_at';
    public const UPDATED_AT               = 'updated_at';
    public const SUBSCRIPTION_TYPE        = 'subscription_type';
    public const SUBSCRIPTION_START_DATE  = 'subscription_start_date';
    public const DISCOUNT_TYPE            = 'dicount_type';
    public const DISCOUNT_VALUE           = 'dicount_value';
    public const SUBSCRIPTION_END_TYPE    = 'subscription_end_type';
    public const SUBSCRIPTION_END_VALUE   = 'subscription_end_value';
    public const NEXT_DATE                = 'next_date';
    public const LAST_ACTION_DATE         = 'last_action_date';
    public const LAST_ACTION_STATUS       = 'last_action_status';
    public const STATUS                   = 'status';
    public const QTY                      = 'qty';

    /**
     * Get ID
     *
     * @return int|null
     */
    public function getId();

    /**
     * Set ID
     *
     * @param int $id
     * @return DataInterface
     */
    public function setId($id);
    
    /**
     * Get CustomerId
     *
     * @return int|null
     */
    public function getCustomerId();

    /**
     * Set CustomerId
     *
     * @param int $customerId
     * @return DataInterface
     */
    public function setCustomerId($customerId);
    
    /**
     * Get ProductId
     *
     * @return int|null
     */
    public function getProductId();

    /**
     * Set ProductId
     *
     * @param int $productId
     * @return DataInterface
     */
    public function setProductId($productId);
    
    /**
     * Get CreatedAt
     *
     * @return int|null
     */
    public function getCreatedAt();

    /**
     * Set CreatedAt
     *
     * @param int $createdAt
     * @return DataInterface
     */
    public function setCreatedAt($createdAt);

    /**
     * Get UpdatedAt
     *
     * @return int|null
     */
    public function getUpdatedAt();

    /**
     * Set Video Status
     *
     * @param int $updatedAt
     * @return DataInterface
     */
    public function setUpdatedAt($updatedAt);

    /**
     * Get Subscription Type
     *
     * @return int|null
     */
    public function getSubscriptionType();

    /**
     * Set Video Status
     *
     * @param int $subscriptionType
     * @return DataInterface
     */
    public function setSubscriptionType($subscriptionType);

    /**
     * Get Subscription Type
     *
     * @return int|null
     */
    public function getSubscriptionStartDate();

    /**
     * Set Video Status
     *
     * @param int $subscriptionStartDate
     * @return DataInterface
     */
    public function setSubscriptionStartDate($subscriptionStartDate);

    /**
     * Get Dicount Type
     *
     * @return int|null
     */
    public function getDicountType();

    /**
     * Set Video Status
     *
     * @param int $dicountType
     * @return DataInterface
     */
    public function setDicountType($dicountType);

    /**
     * Get Dicount Value
     *
     * @return int|null
     */
    public function getDicountValue();

    /**
     * Set Dicount Value
     *
     * @param int $dicountValue
     * @return DataInterface
     */
    public function setDicountValue($dicountValue);

    /**
     * Get Subscription End Date
     *
     * @return int|null
     */
    public function getSubscriptionEndType();

    /**
     * Set Subscription End Date
     *
     * @param int $subscriptionEndType
     * @return DataInterface
     */
    public function setSubscriptionEndType($subscriptionEndType);

    /**
     * Get Subscription End Date
     *
     * @return int|null
     */
    public function getSubscriptionEndValue();

    /**
     * Set Subscription End Date
     *
     * @param int $subscriptionEndValue
     * @return DataInterface
     */
    public function setSubscriptionEndValue($subscriptionEndValue);

    /**
     * Get Next Date
     *
     * @return int|null
     */
    public function getNextDate();

    /**
     * Set Next Date
     *
     * @param int $nextDate
     * @return DataInterface
     */
    public function setNextDate($nextDate);

    /**
     * Get Last Action Date
     *
     * @return int|null
     */
    public function getLastActionDate();

    /**
     * Set Last Action Date
     *
     * @param int $lastActionDate
     * @return DataInterface
     */
    public function setLastActionDate($lastActionDate);

    /**
     * Get Last Action Status
     *
     * @return int|null
     */
    public function getLastActionStatus();

    /**
     * Set Last Action Status
     *
     * @param int $lastActionStatus
     * @return DataInterface
     */
    public function setLastActionStatus($lastActionStatus);

    /**
     * Get Status
     *
     * @return int|null
     */
    public function getStatus();

    /**
     * Set Video Status
     *
     * @param int $status
     * @return DataInterface
     */
    public function setStatus($status);

    /**
     * Get Order Item Qty
     *
     * @return int|null
     */
    public function getQty();

    /**
     * Set Order Item Qty
     *
     * @param int $qty
     * @return DataInterface
     */
    public function setQty($qty);
}
