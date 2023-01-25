<?php

namespace Ecomm\Subscription\Controller\Adminhtml\Index;

use Magento\Framework\Controller\ResultFactory;

//class Edit extends \Magento\Backend\App\Action 
use Magento\Backend\Model\Session;
use Ecomm\Subscription\Model\SubscriptionCron;
use Ecomm\Subscription\Model\SubscriptionCronFactory;
use Ecomm\Subscription\Api\SubscriptionCronRepositoryInterface;
use Ecomm\Subscription\Model\ResourceModel\SubscriptionCron\CollectionFactory;
use Magento\Framework\View\Result\PageFactory;

class Edit extends \Magento\Framework\App\Action\Action
{
    /**
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute() 
    {
       $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
       $resultPage->getConfig()->getTitle()->prepend(__('Edit Record'));
       return $resultPage;

    }
}