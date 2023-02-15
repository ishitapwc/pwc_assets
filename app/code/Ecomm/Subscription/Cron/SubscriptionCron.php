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
use Ecomm\Subscription\Model\SubscriptionOrderFactory;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Translate\Inline\StateInterface;
use \Magento\Sales\Model\Order\Email\Sender\OrderSender;


class SubscriptionCron
{
    private const FIXED_AMOUNT = 'Fixed Amount';
    private const PERCENTAGE_PRICE = 'Percentage on product Price';
    private const NOT_ACTIVE = 'Not Active';

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
     * @var SubscriptionOrderFactory
     */
    protected $subscriptionOrder;

    /**
     * TransportBuilder
     *
     * @var TransportBuilder
     */

    protected $_transportBuilder;

    /**
     * StateInterface
     *
     * @var StateInterface
     */
    protected $inlineTranslation;

    /**
     * @var OrderSender
     */
    protected $orderSender;

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
     * @param SubscriptionOrderFactory $subscriptionOrder
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
        Order $order,
        SubscriptionOrderFactory $subscriptionOrder,
        TransportBuilder $_transportBuilder,
        StateInterface $inlineTranslation,
        OrderSender $orderSender
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
        $this->subscriptionOrder = $subscriptionOrder;
        $this->_transportBuilder = $_transportBuilder;
        $this->inlineTranslation = $inlineTranslation;
        $this->orderSender = $orderSender;
    }

    /**
     * Get Discount Price
     *
     * @return array
     */
    public function execute()
    {
        //die;
        $runnerData = $this->subscriptionCronRepositoryInterface->getCronFilter();
        $reports = [];
       
        if (count($runnerData) > 0) {
            
            foreach ($runnerData as $lodder) {
                $insert = [];
                $stopService = $this->getEnd($lodder);
                if ($stopService['status'] != 'End') {
                    $report = $this->cronOrderCreater($stopService, $lodder);
                    $insert['subscription'] = $report['subscription']->getData();
                    $insert['order_list'] = $report['order_list'];
                    $insert['status'] = $report['status'];
                } else {
                    $reports[$lodder->getId()] = $stopService;
                    $stopService['value']->save();
                    $insert['subscription'] = $stopService['value']->getData();
                    $insert['status'] = 'end';
                }
                $reports[$lodder->getId()] = $insert;
            }
            $this->logger->info('order '. json_encode($reports));
        } else {
            $this->logger->info('Subscription data null ');
        }
    }

    /**
     * Get Discount Price
     *
     * @param array $stopService
     * @param array $lodder
     * @return array
     */
    private function cronOrderCreater($stopService, $lodder)
    {
        
        $load = $stopService['value'];
        $store=$this->storeManager->getStore(1);
        $websiteId = $this->storeManager->getStore()->getWebsiteId();
        $cartId = $this->cartManagementInterface->createEmptyCart();
        $quote = $this->cartRepositoryInterface->get($cartId);
        $customer= $this->customerRepository->getById($load->getCustomerId());
        $customerEmail = $customer->getEmail();
        $quote->setCurrency();
        $quote->assignCustomer($customer);
        $quote->setCustomerIsGuest(0);
        $quote->setStore($store);
        $product=$this->product->load($load->getProductId());
        $product->setPrice($this->getDiscoutPrice($product->getPrice(), $lodder->getData('dicount_type'), $lodder->getData('dicount_value') ));
        
        $quote->addProduct($product, $load->getQty());

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
        // Subscription Purchase Mail Start
            $this->subscriptionPurchaseEmail($customerEmail);
        // Subscription Purchase Mail End

        // Create Order From Quote
        $quote = $this->cartRepositoryInterface->get($quote->getId());
        $orderId = $this->cartManagementInterface->placeOrder($quote->getId());
        $order = $this->order->load($orderId);
        $this->orderSender->send($order);
        $orderSave = $this->saveOrderDetails($quote, $order, $stopService['value']);
        return $orderSave;
    }

    public function subscriptionPurchaseEmail($customerEmail)
    {
        try {
            $writer = new \Zend_Log_Writer_Stream(BP . '/var/log/subscription_email.log');
            $logger = new \Zend_Log();
            $logger->addWriter($writer);
            $logger->info('Mail Sending Start');
            $templateOptions = ['area' => \Magento\Framework\App\Area::AREA_FRONTEND, 'store' => $this->storeManager->getStore()->getId()];
            $templateVars = [
                                'store' => $this->storeManager->getStore(),
                                'message'   => 'As per your subscription plan, order has been placed.',
                                'feature1' => 'Cancel at any time, No contracts or commitments. '
                            ];
            $from = ['email' => "info@pwc.com", 'name' => 'Subscription Order Placed'];
            $this->inlineTranslation->suspend();
            
            $to = [$customerEmail];
            $transport = $this->_transportBuilder->setTemplateIdentifier('subscription_purchase')
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

    /**
     * Get Discount Price
     *
     * @param array $loadder
     * @return array
     */
    private function getEnd($loadder):array
    {
        $status = 'Not_End';
        $customer= $this->customerRepository->getById($loadder->getCustomerId());
        $customerEmail = $customer->getEmail();
        switch ($loadder->getSubscriptionEndType()) {
            case 'Date':
                if ($loadder->getSubscriptionEndValue() == date('Y-m-d')) {
                    $loadder->setStatus(false);
                    $status = 'End';
                    $this->subscriptionEndMail($customerEmail, 'Date', $loadder->getproductId());
                }
                return ['status'=>$status,'value'=>$loadder];
            case 'Cycle':
                if ($loadder->getSubscriptionEndValue() == '0') {
                    $loadder->setStatus(false);
                    $status = 'End';
                    $this->subscriptionEndMail($customerEmail, 'Cycle',$loadder->getproductId());
                } else {
                    $loadder->setSubscriptionEndValue($loadder->getSubscriptionEndValue()-1);
                }
                return ['status'=>$status,'value'=>$loadder];
            case 'Until':
                if ($loadder->getSubscriptionEndValue() == 'Yes') {
                    $loadder->setStatus(false);
                    $status = 'End';
                    $this->subscriptionEndMail($customerEmail, 'Until', $loadder->getproductId());
                }
                return ['status'=>$status,'value'=>$loadder];
            default:
                throw new InputException(__('Something Went Wrong'));
        }
    }


    public function subscriptionEndMail($customerEmail, $type, $productId){
        try {

            $product = $this->product->load($productId);
            $product_name = $product->getName();
            $product_price = $product->getPrice();

            $writer = new \Zend_Log_Writer_Stream(BP . '/var/log/subscription_email.log');
            $logger = new \Zend_Log();
            $logger->addWriter($writer);
            $logger->info('Mail Sending Start');
            $templateOptions = ['area' => \Magento\Framework\App\Area::AREA_FRONTEND, 'store' => $this->storeManager->getStore()->getId()];
            if($type == 'Until'){
                $templateVars = [
                    'store' => $this->storeManager->getStore(),
                    'message'   => 'We have successfully cancelled your subscription. Please renew your subscription anytime by visiting our site.',
                    'msg' => 'Obviously we love to have you back.',
                    'product'=> $product_name,
                    'product_price'=>$product_price
                ];
            }elseif($type == 'Cycle'){
                $templateVars = [
                    'store' => $this->storeManager->getStore(),
                    'message'   => 'Based on your subscription plan, today is the last day of the subscription. Please renew your subscription anytime by visiting our site.',
                    'msg' => 'Obviously we love to have you back.',
                    'product'=> $product_name,
                    'product_price'=>$product_price
                ];
            }elseif($type == 'Date'){
                $templateVars = [
                    'store' => $this->storeManager->getStore(),
                    'message'   => 'As per your subscription end date, we will cancelled your subscription plan, effective from today.  Please renew your subscription anytime by visiting our site',
                    'msg' => 'Obviously we love to have you back.',
                    'product'=> $product_name,
                    'product_price'=>$product_price
                ];
            }
            $from = ['email' => "info@pwc.com", 'name' => 'Subscription Expires'];
            $this->inlineTranslation->suspend();
            $to = [$customerEmail];
            $transport = $this->_transportBuilder->setTemplateIdentifier('subscription_cancel')
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

    /**
     * Get Discount Price
     *
     * @param Quote $quote
     * @param Order $order
     * @param SubscriptionCronRepositoryInterface $loader
     */
    public function saveOrderDetails($quote, $order, $loader)
    {
        $subscriptionOrder =  $this->subscriptionOrder->create();
        $subscriptionOrder->setCustomerId($loader->getCustomerId());
        $subscriptionOrder->setProductId($loader->getProductId());
        $subscriptionOrder->setSubscriptionCronId($loader->getId());
        $subscriptionOrder->setQuoteId($quote->getId());
        if ($order->getIncrementId()) {
            $subscriptionOrder->setOrderId($order->getId());
            $subscriptionOrder->setStatus(true);
        } else {
            $subscriptionOrder->setOrderId($order->getId());
            $subscriptionOrder->setStatus(false);
        }
        try {
            $subscriptionOrder->save();
            $loader->setLastActionStatus(true);
            $loader->setLastActionDate(date("Y-m-d"));
            $loader->setNextDate($this->getSubscriptionDate($loader->getSubscriptionType()));
            $this->subscriptionCronRepositoryInterface->save($loader);
        } catch (\Exception $e) {
            $loader->setLastActionStatus(false);
            $loader->setLastActionDate(date("Y-m-d"));
            $loader->setNextDate($this->getSubscriptionDate($loader->getSubscriptionType()));
            $this->subscriptionCronRepositoryInterface->save($loader);
            return ['status'=>'error', 'order_list'=>json_encode($e), 'subscription'=>$loader];
        }
        return ['order_list'=>$subscriptionOrder->getData(),
        'status'=>'success', 'subscription'=>$loader];
    }

    /**
     * Get Discount Price
     *
     * @param string $type
     */
    public function getSubscriptionDate($type)
    {

        switch ($type) {
            case 0:
                return date("Y-m-d", strtotime("+1 day"));
            case 1:
                return date("Y-m-d", strtotime("+2 day"));
            case 2:
                return date("Y-m-d", strtotime("+1 week"));
            case 3:
                return date("Y-m-d", strtotime("+2 week"));
            case 4:
                return date("Y-m-d", strtotime("+1 month"));
        }
    }

    /**
     * Get Discount Price
     *
     * @param string $price
     * @param string $discountType
     * @param string $values
     */
    public function getDiscoutPrice($price, $discountType, $values)
    {

        if ($discountType == 1) {
            return $values;
        } elseif ($discountType == 0) {
            $value = ($price /100) * $values;
            return $price - $value;
        } else {
            return self::NOT_ACTIVE;
        }
    }
}
