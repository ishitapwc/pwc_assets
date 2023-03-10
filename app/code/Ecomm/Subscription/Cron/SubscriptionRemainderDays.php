<?php
namespace Ecomm\Subscription\Cron;

use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Store\Model\StoreManagerInterface;
use Ecomm\Subscription\Api\SubscriptionCronRepositoryInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Catalog\Model\Product;

class SubscriptionRemainderDays
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

    protected $product;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */

    public $scopeConfig;

    
    public function __construct(
        TransportBuilder $_transportBuilder,
        SubscriptionCronRepositoryInterface $subscriptionCronRepositoryInterface,
        CustomerRepositoryInterface $customerRepository,
        StateInterface $inlineTranslation,
        StoreManagerInterface $storeManager,
        Product $product,
        \Magento\Sales\Api\OrderRepositoryInterface $orderRepository,
        \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        $this->storeManager     = $storeManager;
        $this->_transportBuilder = $_transportBuilder;
        $this->inlineTranslation = $inlineTranslation;
        $this->orderRepository = $orderRepository;
        $this->product = $product;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->subscriptionCronRepositoryInterface = $subscriptionCronRepositoryInterface;
        $this->customerRepository = $customerRepository;
        $this->scopeConfig = $scopeConfig;
    }

    public function execute()
    {
        try {
            $billingRemainderWithDays = $this->scopeConfig->getValue(
                'subscription/general/billing_days_remainder',
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            );
            $writer = new \Zend_Log_Writer_Stream(BP . '/var/log/subscription_email.log');
            $logger = new \Zend_Log();
            $logger->addWriter($writer);
            $logger->info('Mail Sending Start');
            $emailData = $this->subscriptionCronRepositoryInterface->getCronEmailFilter();
            if(count($emailData) > 0){
                foreach($emailData as $list)
                {
                    $customer= $this->customerRepository->getById($list->getCustomerId());
                    $customerEmail = $customer->getEmail();
                    $nextDate = $list->getNextDate();

                    $product = $this->product->load($list->getProductId());
                    $product_name = $product->getName();
                    $product_price = $product->getPrice();


                    $now = time();
                    $your_date = strtotime($nextDate);
                    $datediff = $now - $your_date;

                    $dateCount =  abs(round($datediff / (60 * 60 * 24)));

                    if($dateCount < $billingRemainderWithDays){

                        $writer = new \Zend_Log_Writer_Stream(BP . '/var/log/custom.log');
                        $logger = new \Zend_Log();
                        $logger->addWriter($writer);
                        $logger->info('Mail Sending Start');


                        $templateOptions = ['area' => \Magento\Framework\App\Area::AREA_FRONTEND, 'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID];
                        $templateVars = [
                                'message'   => 'Subscription is going to expire in  '.$dateCount.' day(s).',
                                'name' => $customer->getFirstName()." ".$customer->getLastName(),
                                'date' => $list->getNextDate(),
                                'product' => $product_name,
                                'product_price' => $product_price,
                                'days' => $dateCount
                                ];
                        $from = ['email' => "info@pwc.com", 'name' => 'Subscription Remainder Days'];
                        $this->inlineTranslation->suspend();
                        $to = [$customerEmail];
                        $transport = $this->_transportBuilder->setTemplateIdentifier('subscription_remainder_days')
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
        } catch (\Exception $e) {
            $logger->info('Subscription Email Error Log :'.json_encode($e));
        }
    }
    
}