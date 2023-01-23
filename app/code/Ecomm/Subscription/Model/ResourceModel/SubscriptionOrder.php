<?php

/**
 * PwC India
 *
 * @category Magento
 * @package BekaertSWSb2B_MySku
 * @author PwC India
 * @license GNU General Public License ("GPL") v3.0
 */

declare(strict_types=1);

namespace Ecomm\Subscription\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Table Connection
 */
class SubscriptionOrder extends AbstractDb
{
    /**
     * Connection
     */
    protected function _construct()
    {

        $this->_init('subscription_order', 'entity_id');
    }
}
