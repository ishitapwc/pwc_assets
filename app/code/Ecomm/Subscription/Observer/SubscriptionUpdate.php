<?php
namespace Ecomm\Subscription\Observer;
 
use Magento\Framework\Event\ObserverInterface;
use Psr\Log\LoggerInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\InputException;

class SubscriptionUpdate implements ObserverInterface
{
    /**
     * @var LoggerInterface
     */
    protected $logger;
 
    /**
     * @param LoggerInterface $logger
     * @param RequestInterface $request
     */
    public function __construct(
        LoggerInterface $logger,
        RequestInterface $request
    ) {
        $this->logger = $logger;
        $this->request = $request;
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
         $item = $observer->getQuoteItem();
            $item = ( $item->getParentItem() ? $item->getParentItem() : $item );
            $product = $item->getProduct();
        if ($this->activeSubscription($item)) {
                $this->logger->info('SubscriptionUpdate '. $this->request->getParam('subscription_type'));
                $this->logger->info('SubscriptionActive '. $this->request->getParam('subscription_active'));
                $this->logger->info('SubscriptionDate '. $this->request->getParam('subscription_start_date'));
        }

            return $this;
    }

    /**
     * Active Subscription.
     *
     * @param \Magento\Framework\Event\Observer $item
     *
     * @return $this
     */
    public function activeSubscription($item)
    {
        $subscription = $this->request->getParam('subscription');
        $subscriptionType = $this->request->getParam('frequency');
        $subscriptionStartDate = $this->request->getParam('startdate');
        $subscriptionStartDate = date('Y-m-d', strtotime($subscriptionStartDate));
        $subscriptionEndType = $this->request->getParam('subscription_end_by');
        
        if ($subscription == 1) {
            $subscriptionEnd = $this->getEndValue($subscriptionEndType);
            $item->setData('subscription', true);
            $item->setData('frequency', $subscriptionType);
            if ($subscriptionStartDate > date('Y-m-d')) {
                $item->setData('subscription_start_date', $subscriptionStartDate);
            } else {
                throw new InputException(__('Please Select the Start Date'));
            }
            if ($subscriptionEndType) {
                $item->setData('subscription_end_type', $subscriptionEndType);
            } else {
                throw new InputException(__('Please Select the End Type'));
            }
            if ($subscriptionEnd) {
                $item->setData('subscription_end', $subscriptionEnd);
            } else {
                throw new InputException(__('Please Select the End Type Value'));
            }
            
            $this->logger->info('data '. json_encode($item->getData()));
            return true;
        } else {
            $item->setData('subscription', false);
        }
        return false;
    }

    /**
     * Active Subscription.
     *
     * @param string $endTpe
     *
     * @return string
     */
    public function getEndValue($endTpe)
    {
        switch ($endTpe) {
            case 'Date':
                return $this->request->getParam('enddate');
            case 'Cycle':
                return $this->request->getParam('orders');
            case 'Until':
                return 'unitl';
            default:
                throw new InputException(__('Something Went Wrong'));
        }
    }
}