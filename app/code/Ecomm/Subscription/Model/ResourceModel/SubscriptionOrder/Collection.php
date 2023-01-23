<?php

/**
 * PwC India
 *
 * @category Magento
 * @package BekaertSWSb2B_MySku
 * @author PwC India
 * @license GNU General Public License ("GPL") v3.0
 */

namespace Ecomm\Subscription\Model\ResourceModel\SubscriptionOrder;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Ecomm\Subscription\Model\SubscriptionOrder as ModelSubscription;
use Ecomm\Subscription\Model\ResourceModel\SubscriptionOrder as ResourceModelSubscription;

/**
 * Description SubList Database Connection
 */
class Collection extends AbstractCollection
{
    /**
     * Table Name
     *
     * @var $idFieldName
     */
    protected $idFieldName = 'entity_id';

    /**
     * Connection Resource
     */
    public function _construct()
    {

        $this->_init(
            ModelSubscription::class,
            ResourceModelSubscription::class
        );
    }
}
