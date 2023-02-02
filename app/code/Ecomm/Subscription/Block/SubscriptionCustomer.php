<?php
/**
 * PwC India
 *
 * @category Magento
 * @package Ecomm_Subscription
 * @author PwC India
 * @license GNU General Public License ("GPL") v3.0
 */

namespace Ecomm\Subscription\Block;

use Magento\Catalog\Block\Product\View;
use Magento\Customer\Model\Session;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\View\Element\Template;
use Ecomm\Subscription\Api\SubscriptionCronRepositoryInterface;
use Magento\Framework\App\Response\Http;
use Ecomm\Subscription\Api\OrderRepositoryInterface;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Api\ProductRepositoryInterface;
/**
 * Description Subscription
 */
class SubscriptionCustomer extends Template
{
    protected $customerSession;
    protected $subscriptionCronRepositoryInterface;
    protected $response;
    protected $orderRepositoryInterface;
    protected $product;
    protected $_coreRegistry;

    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;

    public function __construct(
        Context $context,
        Session $customerSession,
        SubscriptionCronRepositoryInterface $subscriptionCronRepositoryInterface,
        Http $response,
        OrderRepositoryInterface $orderRepositoryInterface,
        Product $product,
        \Magento\Catalog\Block\Product\Context $_coreRegistry,
        ProductRepositoryInterface $productRepository,
        array $data = []
    ) {
        $this->customerSession = $customerSession;
        $this->subscriptionCronRepositoryInterface = $subscriptionCronRepositoryInterface;
        $this->response = $response;
        $this->orderRepositoryInterface = $orderRepositoryInterface;
        $this->product = $product;
        $this->_coreRegistry = $_coreRegistry->getRegistry();
        $this->productRepository = $productRepository;
        parent::__construct($context, $data);
    }
    public function execute()
    {

    }

    public function getCustomer()
    {

        $customer = $this->customerSession->getCustomer();

        if ($customer->getId()) {
            return $customer;
        }
       // $this->response->setRedirect('/customer/account/login');
       return null;
    }

    public function getSubscriptionData($customerId)
    {
        $data = $this->subscriptionCronRepositoryInterface->getByCustomerId($customerId);
        foreach ($data as $list) {
            $product=$this->product->load($list->getProductId());
            $list->setData('product', $product);
        }
        return $data;
    }

    public function getLabel($product, $id):string
    {
        $atrr = $product->getResource()->getAttribute('subscription_discount_type');
        $type = $atrr->getSource()->getOptionText($id);

        return $type;
    }

    public function getOrderList(){
        
        $dataId = $this->getRequest()->getParam('id');
        if ($dataId != null) {
            return $this->orderRepositoryInterface->getOrderList($dataId);
        }
        return null;
    }
    /**
     * Retrieve current product model
     *
     * @return \Magento\Catalog\Model\Product
     */
    public function getProduct()
    {
        if (!$this->_coreRegistry->registry('product') && $this->getProductId()) {
            $product = $this->productRepository->getById($this->getProductId());
            $this->_coreRegistry->register('product', $product);
        }
        return $this->_coreRegistry->registry('product');
    }

    /**
     * Get Subscription All Data
     *
     * @return array
     */
    public function getSubscription():array
    {
        $subData = [];
        if ( $this->getCustomer() !=  null) {
            $product  = $this->getProduct();
            $attr = $product->getResource()->getAttribute('subscription_type');
            $optionValue = [];
            $atrr = $product->getResource()->getAttribute('subscription_discount_type');
            $type = $atrr->getSource()->getOptionText($product->getSubscriptionDiscountType());
            
            if ($product->getSubscriptionType() != null) {
            
                foreach (explode(',', $product->getSubscriptionType()) as $option) {
                    $optionValue[$option]= $attr->getSource()->getOptionText($option);
                }
            }
            $subData['status'] = $product->getSubscriptionStatus();
            $subData['name']   = $product->getSubscriptionName();
            $subData['intialfee_status']   = $product->getSubscriptionIntialfee();
            $subData['intialfee_amount']   = $product->getSubscriptionIntialfeeValue();
            $subData['freeshipping']   = $product->getSubscriptionFreeshipping();
            $subData['type']   = $optionValue;
            $subData['discount_status']   =  $product->getSubscriptionDiscount();
            $subData['discount_type']   = $type;
            $subData['discount_value']   = $product->getSubscriptionDiscountValue();
        }
        return $subData;
    }

    /**
     * Get Discount Price
     *
     * @return array
     */
    public function getSubscribed()
    {

        $product  = $this->getProduct();
        $customerId =  $this->getCustomer();
        try{
            $data = $this->subscriptionCronRepositoryInterface->getSub($product->getId(), $customerId->getId());
            if ($data->getData() != null) {
                return true;
            }
        }catch(\Exception $e){
            return false;
        }
       
        return false;
    }
    
    /**
     * Get Discount Price
     *
     * @return array
     */
    public function getDiscountPrice()
    {
        $product  = $this->getProduct();
        $price = 0;
        if ($product->getSubscriptionStatus() == null || $product->getSubscriptionStatus() == 0
        || $product->getSubscriptionDiscount() == null) {
            return self::NOT_ACTIVE;
        } else {
            $atrr = $product->getResource()->getAttribute('subscription_discount_type');
            $type = $atrr->getSource()->getOptionText($product->getSubscriptionDiscountType());
            if ($type == 1) {
                return $product->getSubscriptionDiscountValue();
            } elseif ($type == 0) {
                $value = ($product->getFinalPrice() /100) * $product->getSubscriptionDiscountValue();
                return $product->getFinalPrice() - $value;
            } else {
                return self::NOT_ACTIVE;
            }
        }
    }
}
