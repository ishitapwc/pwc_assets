<?php

namespace Ecomm\Subscription\Controller\Myaccount;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Customer\Model\Session;
use Ecomm\Subscription\Api\SubscriptionCronRepositoryInterface;
use Ecomm\Subscription\Model\SubscriptionCronFactory;

/**
 * Description SubscriptionCron Table AbstractModel
 */
class SubEnd extends Action
{
    /**
     * @var ResultFactory
     */
    protected $pageFactory;

    /**
     * @var Session
     */
    protected $customerSession;
    
    /**
     * @var Session
     */
    protected $subscriptionCronRepositoryInterface;

    /**
     * @var SubscriptionCronFactory
     */
    protected $subscriptionCronFactory;

    /**
     * Get Details
     *
     * @param Context $context
     * @param PagResultFactoryeFactory $pageFactory
     * @param Session $customerSession
     * @param SubscriptionCronRepositoryInterface $subscriptionCronRepositoryInterface
     * @param SubscriptionCronFactory $subscriptionCronFactory
     */
    public function __construct(
        Context $context,
        ResultFactory $pageFactory,
        Session $customerSession,
        SubscriptionCronRepositoryInterface $subscriptionCronRepositoryInterface,
        SubscriptionCronFactory $subscriptionCronFactory
    ) {
        $this->pageFactory = $pageFactory;
        $this->customerSession = $customerSession;
        $this->subscriptionCronRepositoryInterface = $subscriptionCronRepositoryInterface;
        $this->subscriptionCronFactory = $subscriptionCronFactory;
        return parent::__construct($context);
    }

    /**
     * Description SubscriptionCron Table AbstractModel
     *
     * @return PageFactory
     */
    public function execute()
    {
        $dataId = $this->getRequest()->getParam('id');
        $customer = $this->customerSession->getCustomer();
        if ($customer->getId()) {
            $data =  $this->subscriptionCronFactory->create()->getCollection()
            ->addFieldToFilter('entity_id', $dataId)
            ->addFieldToFilter('customer_id', $customer->getId());
  
            if (count($data) == 1) {
                foreach ($data as $end) {
                    if ($end->getId() > 0) {
                        $end->setStatus(false);
                        $this->subscriptionCronRepositoryInterface->save($end);
                    }
                }
            }

        }
        $redirect = $this->pageFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_REDIRECT);
        $redirect->setUrl('/subscription/myaccount/subscription/');
        return $redirect;
    }
}
