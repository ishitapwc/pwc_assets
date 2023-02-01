<?php

namespace Ecomm\Subscription\Controller\Myaccount;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Customer\Model\Session;
use Ecomm\Subscription\Api\SubscriptionCronRepositoryInterface;
use Ecomm\Subscription\Model\SubscriptionCronFactory;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Store\Model\StoreManagerInterface;

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
     * StoreManager
     *
     * @var StoreManagerInterface
     */

    protected $storeManager;

    /**
     * TransportBuilder
     *
     * @var TransportBuilder
     */

    protected $_transportBuilder;

    /**
     * InlineTranslation
     *
     * @var StateInterface
     */

    protected $inlineTranslation;

    /**
     * @var SubscriptionCronRepositoryInterface
     */
    protected $customerRepository;
    
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
        SubscriptionCronFactory $subscriptionCronFactory,
        StateInterface $inlineTranslation,
        StoreManagerInterface $storeManager,
        CustomerRepositoryInterface $customerRepository,
        TransportBuilder $_transportBuilder
    ) {
        $this->storeManager     = $storeManager;
        $this->_transportBuilder = $_transportBuilder;
        $this->inlineTranslation = $inlineTranslation;
        $this->pageFactory = $pageFactory;
        $this->customerSession = $customerSession;
        $this->subscriptionCronRepositoryInterface = $subscriptionCronRepositoryInterface;
        $this->subscriptionCronFactory = $subscriptionCronFactory;
        $this->customerRepository = $customerRepository;
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
            $customer= $this->customerRepository->getById($customer->getId());
            $customerEmail = $customer->getEmail();
            $name = $customer->getFirstName()." ".$customer->getLastName();
            if (count($data) == 1) {
                foreach ($data as $end) {
                    if ($end->getId() > 0) {
                        $end->setStatus(false);
                        $this->subscriptionCronRepositoryInterface->save($end);
                        $this->subscriptionCancellationMail($customerEmail, $name);
                    }
                }
            }

        }
        $redirect = $this->pageFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_REDIRECT);
        $redirect->setUrl('/subscription/myaccount/subscription/');
        return $redirect;
    }

    public function subscriptionCancellationMail($customerEmail, $name){
        try {
            $writer = new \Zend_Log_Writer_Stream(BP . '/var/log/subscription_email.log');
            $logger = new \Zend_Log();
            $logger->addWriter($writer);
            $logger->info('Mail Sending Start');
            $templateOptions = ['area' => \Magento\Framework\App\Area::AREA_FRONTEND, 'store' => $this->storeManager->getStore()->getId()];
            $templateVars = [
                                'store' => $this->storeManager->getStore(),
                                'message'   => 'We have successfully cancelled your subscription. Please renew your subscription anytime by visiting our site.',
                                'feature1' => 'Obviously we love to have you back.',
                                'name' => $name
                            ];
            $from = ['email' => "info@pwc.com", 'name' => 'Subscription Cancellation!!'];
            $this->inlineTranslation->suspend();
            
            $to = [$customerEmail];
            $transport = $this->_transportBuilder->setTemplateIdentifier('subscription_user_cancel')
                            ->setTemplateOptions($templateOptions)
                            ->setTemplateVars($templateVars)
                            ->setFrom($from)
                            ->addTo($to)
                            ->getTransport();
            $transport->sendMessage();
            $this->inlineTranslation->resume();
        } catch (\Exception $e) {
            $logger->info('Subscription Email Error Log :'.json_encode($e));
        }
    }
}
