<?php
namespace Ecomm\Subscription\Observer;
 
use Magento\Framework\Event\ObserverInterface;
use Psr\Log\LoggerInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\InputException;
use Ecomm\Subscription\Model\SubscriptionCronFactory;
use Magento\Catalog\Model\ProductRepository;
use Ecomm\Subscription\Model\ProductRunner;

class BeforePlaceOrder implements ObserverInterface
{
    private const FIXED_AMOUNT = 'Fixed Amount';
    private const PERCENTAGE_PRICE = 'Percentage on product Price';
    private const NOT_ACTIVE = 'Not Active';

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var SubscriptionCronFactory
     */
    protected $subscriptionCronFactory;

    /**
     * @var ProductRepository
     */
    protected ProductRepository $product;

    /**
     * @var ProductRunner
     */
    protected ProductRunner $productRunner;
 
    /**
     * @param LoggerInterface $logger
     * @param RequestInterface $request
     * @param SubscriptionCronFactory $subscriptionCronFactory
     * @param ProductRepository $product
     * @param ProductRunner $productRunner
     */
    public function __construct(
        LoggerInterface $logger,
        RequestInterface $request,
        SubscriptionCronFactory $subscriptionCronFactory,
        ProductRepository $product,
        ProductRunner $productRunner
    ) {
        $this->logger = $logger;
        $this->request = $request;
        $this->subscriptionCronFactory = $subscriptionCronFactory;
        $this->product = $product;
        $this->productRunner = $productRunner;
    }
 
    /**
     * Add to cart event handler.
     *
     * @param \Magento\Framework\Event\Observer $observer
     *
     * @return $this
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $event = $observer->getEvent();
        $quote = $event->getQuote();
        $order = $event->getOrder();

        foreach ($quote->getAllItems() as $quoteItems) {
            foreach ($order->getAllItems() as $orderItems) {
                if ($orderItems->getQuoteItemId() == $quoteItems->getId()) {
                    $orderItems->setData('subscription', $quoteItems->getData('subscription'));
                    $orderItems->setData('frequency', $quoteItems->getData('frequency'));
                    $orderItems->setData('subscription_start_date', $quoteItems->getData('subscription_start_date'));
                    $orderItems->setData('subscription_end_type', $quoteItems->getData('subscription_end_type'));
                    $orderItems->setData('subscription_end', $quoteItems->getData('subscription_end'));
                }
            }
        }
            $this->activeSubscription($order, $quote);

         return $this;
    }

    /**
     * Active Subscription.
     *
     * @param \Magento\Framework\Event\Observer $order
     *
     * @return boolean
     */
    private function activeSubscription($order)
    {
        foreach ($order->getAllItems() as $items) {
            $productData = $this->product->getById($items->getProductId());
            if ($items->getData('subscription')) {
                if ($this->productRunner->productValidation($productData)) {
                    $discount = $this->productRunner->productDiscount($productData);
                    $data = $this->subscriptionCronFactory->create();
                    $listCron = $data->getCollection()
                    ->addFieldToFilter('customer_id', $order->getCustomerId())
                    ->addFieldToFilter('product_id', $items->getProductId());
                    if (count($listCron->getData()) == 0) {
                        $data->setProductId($items->getProductId());
                        $data->setCustomerId($order->getCustomerId());
                        $data->setSubscriptionType($items->getData('frequency'));
                        $data->setSubscriptionStartDate($items->getData('subscription_start_date'));
                        $data->setDicountType($discount['type']);
                        $data->setDicountValue($discount['value']);
                        $data->setSubscriptionEndType($items->getData('subscription_end_type'));
                        $data->setSubscriptionEndValue($items->getData('subscription_end'));
                        $data->setNextDate($items->getData('subscription_start_date'));
                        $data->setLastActionDate(null);
                        $data->setLastActionStatus(null);
                        $data->setStatus(true);
                        $data->save();
                    }
                } else {
                    throw new InputException(__('This sku '.$productData->getSku().' subscription not valid'));
                }

            }
        }

        return true;
    }
}
