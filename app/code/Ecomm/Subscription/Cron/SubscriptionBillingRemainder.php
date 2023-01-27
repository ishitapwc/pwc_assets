<?php
namespace Ecomm\Subscription\Cron;

use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Store\Model\StoreManagerInterface;
use Ecomm\Subscription\Api\SubscriptionCronRepositoryInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;

class SubscriptionBillingRemainder
{

    /**
     * @var SubscriptionCronRepositoryInterface
     */
    protected $subscriptionCronRepositoryInterface;

    /**
     * @var CustomerRepository
     */
    protected $customerRepository;


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
     * @var \Magento\Sales\Api\OrderRepositoryInterface
     */
    protected $orderRepository;
    /**
     * @var \Magento\Framework\Api\SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;

    
    public function __construct(
        TransportBuilder $_transportBuilder,
        SubscriptionCronRepositoryInterface $subscriptionCronRepositoryInterface,
        CustomerRepositoryInterface $customerRepository,
        StateInterface $inlineTranslation,
        StoreManagerInterface $storeManager,
        \Magento\Sales\Api\OrderRepositoryInterface $orderRepository,
        \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        $this->storeManager     = $storeManager;
        $this->subscriptionCronRepositoryInterface = $subscriptionCronRepositoryInterface;
        $this->customerRepository = $customerRepository;
        $this->_transportBuilder = $_transportBuilder;
        $this->inlineTranslation = $inlineTranslation;
        $this->orderRepository = $orderRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    public function execute(){

        $emailData = $this->subscriptionCronRepositoryInterface->getCronEmailFilter();
        if(count($emailData) > 0){
            foreach($emailData as $list)
            {
                $customer= $this->customerRepository->getById($list->getCustomerId());
                $customerEmail = $customer->getEmail();
                $writer = new \Zend_Log_Writer_Stream(BP . '/var/log/custom.log');
                $logger = new \Zend_Log();
                $logger->addWriter($writer);
                $logger->info('Mail Sending Start');


                $templateOptions = ['area' => \Magento\Framework\App\Area::AREA_FRONTEND, 'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID];
                $templateVars = [
                        'message'   => 'Subscription Remainder Mail',
                        'name' => $customer->getFirstName()." ".$customer->getLastName(),
                        'date' => $list->getNextDate(),
                        'product' => 'sample'
                        ];
                $from = ['email' => "info@pwc.com", 'name' => 'Subscription Billing Remainder'];
                $this->inlineTranslation->suspend();
                $to = [$customerEmail];
                $transport = $this->_transportBuilder->setTemplateIdentifier('subscription_billing_remainder')
                                ->setTemplateOptions($templateOptions)
                                ->setTemplateVars($templateVars)
                                ->setFrom($from)
                                ->addTo($to)
                                ->getTransport();
                $transport->sendMessage();
                $this->inlineTranslation->resume();

                $logger->info('Mail Sent End');
            }
        }
    }
    
}