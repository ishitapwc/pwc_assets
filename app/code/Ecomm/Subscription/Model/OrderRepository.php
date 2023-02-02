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

use Ecomm\Subscription\Model\ResourceModel\SubscriptionOrder;
use Ecomm\Subscription\Model\SubscriptionOrderFactory;
use Ecomm\Subscription\Model\ResourceModel\SubscriptionOrder\Collection;
use Ecomm\Subscription\Api\Data\SubscriptionOrderInterface;
use Ecomm\Subscription\Api\OrderRepositoryInterface;
use Magento\Sales\Api\OrderRepositoryInterface as OrderInterface;

/**
 * Description SubscriptionCron Table AbstractModel
 */
class OrderRepository implements OrderRepositoryInterface
{

    /**
     * @var SubscriptionOrder
     */
    protected $subscriptionOrder;
    /**
     * @var SubscriptionOrderFactory
     */
    protected $subscriptionOrderFactory;

    /**
     * @var Collection
     */
    protected $collection;

    /**
     * @var OrderInterface
     */
    protected $orderInterface;

    /**
     * Get Details
     *
     * @param SubscriptionCron $subscriptionOrder
     * @param Collection $collection
     * @param SubscriptionCronFactory $subscriptionOrderFactory
     * @param OrderInterface $orderInterface
     */
    public function __construct(
        SubscriptionOrder $subscriptionOrder,
        Collection $collection,
        SubscriptionOrderFactory $subscriptionOrderFactory,
        OrderInterface $orderInterface
    ) {
        $this->subscriptionOrder = $subscriptionOrder;
        $this->collection = $collection;
        $this->subscriptionOrderFactory = $subscriptionOrderFactory;
        $this->orderInterface = $orderInterface;
    }

    /**
     * Description SubscriptionCron Table AbstractModel
     *
     * @param SubscriptionOrderInterface $subscriptionOrder
     */
    public function save(SubscriptionOrderInterface $subscriptionOrder)
    {
        try {
            return $this->subscriptionOrder->save($subscriptionOrder);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Description SubscriptionCron Table AbstractModel
     *
     * @param int $id
     */
    public function getOrderList($id)
    {

        try {
            $orderList = [];
            $data = $this->subscriptionOrderFactory->create()->getCollection()
            ->addFieldToFilter('subscription_cron_id', $id);
            foreach ($data as $list) {
                try {
                    $order =  $this->orderInterface->get($list->getOrderId());
                    array_push($orderList, $order);

                } catch (\Exception $e) {
                    array_push($orderList, $e->getMessage());
                }
            }
            return $orderList;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
