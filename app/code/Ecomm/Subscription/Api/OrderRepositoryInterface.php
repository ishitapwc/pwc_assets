<?php
/**
 * PwC India
 *
 * @category Magento
 * @package  Ecomm_VideoList
 * @author   PwC India
 * @license  GNU General Public License ("GPL") v3.0
 */

namespace Ecomm\Subscription\Api;

use Ecomm\Subscription\Api\Data\SubscriptionOrderInterface;

interface OrderRepositoryInterface
{
    /**
     * Save SubList.
     *
     * @param Ecomm\Subscription\Api\Data\SubscriptionCronInterface $subscriptionCron
     * @return Ecomm\Subscription\Api\Data\SubscriptionCronInterface
     */
    public function save(SubscriptionOrderInterface $subscriptionOrder);

    /**
     * Save SubList.
     *
     * @param int $id
     * @return SubscriptionOrderInterface[]
     */
    public function getOrderList($id);
}
