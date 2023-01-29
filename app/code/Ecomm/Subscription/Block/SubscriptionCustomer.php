<?php
/**
 * PwC India
 *
 * @category Magento
 * @package Ecomm_Subscription
 * @author PwC India
 * @license GNU General Public License ("GPL") v3.0
 */

namespace Ecomm\Subscription\Block;

use Magento\Catalog\Block\Product\View;
use Magento\Customer\Model\Session;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\View\Element\Template;
use Ecomm\Subscription\Api\SubscriptionCronRepositoryInterface;
use Magento\Framework\App\Response\Http;

/**
 * Description Subscription
 */
class SubscriptionCustomer extends Template
{
    protected $customerSession;
    protected $subscriptionCronRepositoryInterface;
    protected $response;

    public function __construct(
        Context $context,
        Session $customerSession,
        SubscriptionCronRepositoryInterface $subscriptionCronRepositoryInterface,
        Http $response,
        array $data = []
    ) {
        $this->customerSession = $customerSession;
        $this->subscriptionCronRepositoryInterface = $subscriptionCronRepositoryInterface;
        $this->response = $response;
        parent::__construct($context, $data);
    }
    public function execute()
    {

    }

    public function getCustomer()
    {

        $customer = $this->customerSession->getCustomer();

        if ($customer->getId()) {
            return $customer;
        }
        $this->response->setRedirect('/customer/account/login');
    }

    public function getSubscriptionData($customerId)
    {
        return $this->subscriptionCronRepositoryInterface->getByCustomerId($customerId);
    }
}
