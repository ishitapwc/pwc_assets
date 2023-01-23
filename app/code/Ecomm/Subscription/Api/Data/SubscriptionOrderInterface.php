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

interface SubscriptionOrderInterface
{
    /**
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    public const ID                     = 'entity_id';
    public const CUSTOMER_ID            = 'customer_id';
    public const PRODUCT_ID             = 'product_id';
    public const SUBSCRIPTION_CRON_ID   = 'subscription_cron_id';
    public const ORDER_ID               = 'order_id';
    public const DICOUNT_TYPE           = 'dicount_type';
    public const CREATED_AT             = 'created_at';
    public const UPDATED_AT             = 'updated_at';
    public const STATUS                 = 'status';

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
     * Get ProductId
     *
     * @return int|null
     */
    public function getSubscriptionCronId();

    /**
     * Set ProductId
     *
     * @param int $subscriptionCronId
     * @return DataInterface
     */
    public function setSubscriptionCronId($subscriptionCronId);

    /**
     * Get ProductId
     *
     * @return int|null
     */
    public function getOrderId();

    /**
     * Set ProductId
     *
     * @param int $orderId
     * @return DataInterface
     */
    public function setOrderId($orderId);

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
}
