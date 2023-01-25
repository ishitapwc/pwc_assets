<?php

namespace Ecomm\Subscription\Controller\Myaccount;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

/**
 * Description SubscriptionCron Table AbstractModel
 */
class Subscription extends Action
{
    /**
     * @var PageFactory
     */
    protected $pageFactory;

    /**
     * Get Details
     *
     * @param Context $context
     * @param PageFactory $pageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $pageFactory
    ) {
        $this->pageFactory = $pageFactory;
        return parent::__construct($context);
    }

    /**
     * Description SubscriptionCron Table AbstractModel
     *
     * @return PageFactory
     */
    public function execute()
    {
        return $this->pageFactory->create();
    }
}
