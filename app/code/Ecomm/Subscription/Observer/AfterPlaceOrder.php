<?php
namespace Ecomm\Subscription\Observer;
 
use Magento\Framework\Event\ObserverInterface;
use Psr\Log\LoggerInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\InputException;

class AfterPlaceOrder implements ObserverInterface
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
        $order = $observer->getEvent()->getOrder();
        
        $this->logger->info("order " . json_encode($order->getData()));
    }
}
