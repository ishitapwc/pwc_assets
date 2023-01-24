<?php

namespace Ecomm\Subscription\Cron;

use \Psr\Log\LoggerInterface;
use Ecomm\Subscription\Api\SubscriptionCronRepositoryInterface;
use Magento\Quote\Model\QuoteFactory;
use Magento\Customer\Model\CustomerFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Catalog\Model\Product;
use Magento\Quote\Model\QuoteManagement;
use Magento\Customer\Model\AddressFactory;
use Magento\Quote\Api\CartManagementInterface;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Sales\Model\Order;

class SubscriptionCron
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var SubscriptionCronRepositoryInterface
     */
    protected $subscriptionCronRepositoryInterface;

    /**
     * @var SubscriptionCronRepositoryInterface
     */
    protected $quote;

    /**
     * @var SubscriptionCronRepositoryInterface
     */
    protected $customer;

    /**
     * @var SubscriptionCronRepositoryInterface
     */
    protected $storeManager;

    /**
     * @var SubscriptionCronRepositoryInterface
     */
    protected $customerRepository;

    /**
     * @var SubscriptionCronRepositoryInterface
     */
    protected $product;

    /**
     * @var SubscriptionCronRepositoryInterface
     */
    protected $quoteManagement;

    /**
     * @var SubscriptionCronRepositoryInterface
     */
    protected $addressFactory;

    /**
     * @var SubscriptionCronRepositoryInterface
     */
    protected $cartManagementInterface;

    /**
     * @var SubscriptionCronRepositoryInterface
     */
    protected $cartRepositoryInterface;

    /**
     * @var SubscriptionCronRepositoryInterface
     */
    protected $order;

    /**
     * Get Details
     *
     * @param LoggerInterface $logger
     * @param SubscriptionCronRepositoryInterface $subscriptionCronRepositoryInterface
     * @param QuoteFactory $quote
     * @param CustomerFactory $customer
     * @param StoreManagerInterface $storeManager
     * @param CustomerRepositoryInterface $customerRepository
     * @param Product $product
     * @param QuoteManagement $quoteManagement
     * @param AddressFactory $addressFactory
     * @param CartManagementInterface $cartManagementInterface
     * @param CartRepositoryInterface $cartRepositoryInterface
     * @param Order $order
     */
    public function __construct(
        LoggerInterface $logger,
        SubscriptionCronRepositoryInterface $subscriptionCronRepositoryInterface,
        QuoteFactory $quote,
        CustomerFactory $customer,
        StoreManagerInterface $storeManager,
        CustomerRepositoryInterface $customerRepository,
        Product $product,
        QuoteManagement $quoteManagement,
        AddressFactory $addressFactory,
        CartManagementInterface $cartManagementInterface,
        CartRepositoryInterface $cartRepositoryInterface,
        Order $order
    ) {

        $this->logger = $logger;
        $this->subscriptionCronRepositoryInterface = $subscriptionCronRepositoryInterface;
        $this->quote = $quote;
        $this->customer = $customer;
        $this->storeManager = $storeManager;
        $this->customerRepository = $customerRepository;
        $this->product = $product;
        $this->quoteManagement = $quoteManagement;
        $this->addressFactory = $addressFactory;
        $this->cartManagementInterface = $cartManagementInterface;
        $this->cartRepositoryInterface = $cartRepositoryInterface;
        $this->order = $order;
    }

    /**
     * Get Discount Price
     *
     * @return array
     */
    public function execute()
    {
        $runnerData = $this->subscriptionCronRepositoryInterface->getCronFilter();
        if (count($runnerData) > 0) {
            foreach ($runnerData as $lodder) {
                $stopService = $this->getEnd($lodder);
                $report = $this->cronOrderCreater($stopService);
                $this->logger->info('order '. json_encode($report));
            }
        } else {
            $this->logger->info('Data Null ');
        }
    }

    /**
     * Get Discount Price
     *
     * @param array $stopService
     * @return array
     */
    private function cronOrderCreater($stopService)
    {
        $load = $stopService['value'];
        $store=$this->storeManager->getStore(1);
        $websiteId = $this->storeManager->getStore()->getWebsiteId();
        $cartId = $this->cartManagementInterface->createEmptyCart();
        $quote = $this->cartRepositoryInterface->get($cartId);
        $customer= $this->customerRepository->getById($load->getCustomerId());
        $quote->setCurrency();
        $quote->assignCustomer($customer);
        $quote->setCustomerIsGuest(0);
        $quote->setStore($store);
        $product=$this->product->load($load->getProductId());
        $product->setPrice($product->getPrice());
        $quote->addProduct($product, 1);

        $billingAddressId = $customer->getDefaultBilling();
        $billingAddress = $this->addressFactory->create()->load($billingAddressId);
        $quote->getBillingAddress()->addData($billingAddress->getData());

        $shippingAddressId = $customer->getDefaultShipping();
        $shippingAddress = $this->addressFactory->create()->load($shippingAddressId);
        $quote->getShippingAddress()->addData($shippingAddress->getData());
      
        $shippingAddress=$quote->getShippingAddress();
        $shippingAddress->setCollectShippingRates(true)
                        ->collectShippingRates()
                        ->setShippingMethod('flatrate_flatrate');
        $quote->setPaymentMethod('checkmo');
        $quote->setInventoryProcessed(false);
        $quote->getPayment()->importData(['method' => 'checkmo']);
        $quote->save();
 
        // Collect Totals & Save Quote
        $quote->collectTotals()->save();

        // Create Order From Quote
        
        //$order = $this->quoteManagement->submit($quote);
       // Create Order From Quote
        $this->logger->info('quote Id '. $quote->getId());
        $quote = $this->cartRepositoryInterface->get($quote->getId());
        $orderId = $this->cartManagementInterface->placeOrder($quote->getId());
        $order = $this->order->load($orderId);
        if ($order->getId()) {

            $result['order_id']= $order->getIncrementId();
        } else {
            $result=['error'=>1,'msg'=>'Your custom message'];
        }
        return $result;
    }

    /**
     * Get Discount Price
     *
     * @param array $loadder
     * @return array
     */
    private function getEnd($loadder):array
    {
        $data = [];
        $status = 'Not_End';
        switch ($loadder->getSubscriptionEndType()) {
            case 'Date':
                if ($loadder->getSubscriptionEndValue() == date('Y-m-d')) {
                    $loadder->setStatus(false);
                    $status = 'End';
                }
                return ['status'=>$status,'value'=>$loadder];
            case 'Cycle':
                if ($loadder->getSubscriptionEndValue() == 1) {
                    $loadder->setSubscriptionEndValue(0);
                    $loadder->setStatus(false);
                    $status = 'End';
                } else {
                    $loadder->setSubscriptionEndValue($loadder->getSubscriptionEndValue()-1);
                }
                return ['status'=>$status,'value'=>$loadder];
            case 'Until':
                if ($loadder->getSubscriptionEndValue() == 'Yes') {
                    $loadder->setStatus(false);
                    $status = 'End';
                }
                return ['status'=>$status,'value'=>$loadder];
            default:
                throw new InputException(__('Something Went Wrong'));
        }
    }

    /**
     * Get Discount Price
     *
     * @param array $quote
     * @param Address $address
     * @return array
     */
    public function setAddress($quote, $address)
    {
        return '';
    }

    /**
     * Get Discount Price
     *
     * @param array $quote
     * @param Address $address
     * @return array
     */
    public function saveOrderDetails($quote, $address)
    {
        return '';
    }
}
