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

use Ecomm\Subscription\Api\Data\SubscriptionCronInterface;

interface SubscriptionCronRepositoryInterface
{
    /**
     * Save SubList.
     *
     * @param Ecomm\Subscription\Api\Data\SubscriptionCronInterface $subscriptionCron
     * @return Ecomm\Subscription\Api\Data\SubscriptionCronInterface
     */
    public function save(SubscriptionCronInterface $subscriptionCron);

    /**
     * Save SubList.
     *
     * @param string $id
     * @return SubscriptionCronInterface
     */
    public function getById(string $id);

    /**
     * Save SubList.
     *
     * @param array $filterKey
     * @return SubscriptionCronInterface[]
     */
    public function getCronFilter();
}
