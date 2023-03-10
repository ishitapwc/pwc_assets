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

use Ecomm\Subscription\Model\ResourceModel\SubscriptionCron;
use Ecomm\Subscription\Model\SubscriptionCronFactory;
use Ecomm\Subscription\Model\ResourceModel\SubscriptionCron\Collection;
use Ecomm\Subscription\Api\Data\SubscriptionCronInterface;
use Ecomm\Subscription\Api\SubscriptionCronRepositoryInterface;

/**
 * Description SubscriptionCron Table AbstractModel
 */
class SubscriptionCronRepository implements SubscriptionCronRepositoryInterface
{

    /**
     * @var SubscriptionCron
     */
    protected $subscriptionCron;
    /**
     * @var SubscriptionCronFactory
     */
    protected $subscriptionCronFactory;

    /**
     * @var Collection
     */
    protected $collection;

    /**
     * Get Details
     *
     * @param SubscriptionCron $subscriptionCron
     * @param Collection $collection
     * @param SubscriptionCronFactory $subscriptionCronFactory
     */
    public function __construct(
        SubscriptionCron $subscriptionCron,
        Collection $collection,
        SubscriptionCronFactory $subscriptionCronFactory
    ) {
        $this->subscriptionCron = $subscriptionCron;
        $this->collection = $collection;
        $this->subscriptionCronFactory = $subscriptionCronFactory;
    }

    /**
     * Description SubscriptionCron Table AbstractModel
     *
     * @param SubscriptionCronInterface $subscriptionCron
     */
    public function save(SubscriptionCronInterface $subscriptionCron)
    {
        try {
            return $this->subscriptionCron->save($subscriptionCron);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Description SubscriptionCron Table AbstractModel
     *
     * @param empty
     */
    public function getCronFilter()
    {
        return $this->subscriptionCronFactory->create()->getCollection()
        ->addFieldToFilter('status', true)
        ->addFieldToFilter(
            ['next_date','subscription_end_value'],
            [ ['eq'=>date('Y-m-d')],
                    ['in'=>[date('Y-m-d'),0,'Yes']]]
        );
    }

    /**
     * Description SubscriptionCron Table AbstractModel
     *
     * @param empty
     */
    public function getCronEmailFilter()
    {
        return $this->subscriptionCronFactory->create()->getCollection()
        ->addFieldToFilter('status', true);
    }

    /**
     * Description SubscriptionCron Table AbstractModel
     *
     * @param array $id
     */
    public function getByCustomerId($id)
    {
        try {
            return $this->subscriptionCronFactory->create()->getCollection()
            ->addFieldToFilter('customer_id', $id);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Description SubscriptionCron Table AbstractModel
     *
     * @param int $productId
     * @param int $customerId
     */
    public function getSub($productId, $customerId)
    {
        try {
            return $this->subscriptionCronFactory->create()->getCollection()
            ->addFieldToFilter('customer_id', $customerId)
            ->addFieldToFilter('product_id', $productId)
            ->addFieldToFilter('status', 1);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
