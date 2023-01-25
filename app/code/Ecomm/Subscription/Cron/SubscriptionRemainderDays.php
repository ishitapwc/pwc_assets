<?php
namespace Ecomm\Subscription\Cron;

use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Store\Model\StoreManagerInterface;

class SubscriptionRemainderDays
{
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
        StateInterface $inlineTranslation,
        StoreManagerInterface $storeManager,
        \Magento\Sales\Api\OrderRepositoryInterface $orderRepository,
        \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        $this->storeManager     = $storeManager;
        $this->_transportBuilder = $_transportBuilder;
        $this->inlineTranslation = $inlineTranslation;
        $this->orderRepository = $orderRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    public function execute(){

        $writer = new \Zend_Log_Writer_Stream(BP . '/var/log/custom.log');
        $logger = new \Zend_Log();
        $logger->addWriter($writer);
        $logger->info('Mail Sending Start');


        $templateOptions = ['area' => \Magento\Framework\App\Area::AREA_FRONTEND, 'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID];
        $templateVars = [
                'message'   => 'Subscription Remainder With Days'
                ];
        $from = ['email' => "info@pwc.com", 'name' => 'Subscription Remainder Days'];
        $this->inlineTranslation->suspend();
        $to = ['vselvakumar04@gmail.com'];
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