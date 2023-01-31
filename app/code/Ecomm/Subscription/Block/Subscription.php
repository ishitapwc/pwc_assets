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
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Category;

/**
 * Description Subscription
 */
class Subscription extends View
{

    private const FIXED_AMOUNT = 'Fixed Amount';
    private const PERCENTAGE_PRICE = 'Percentage on product Price';
    private const NOT_ACTIVE = 'Not Active';

    /**
     * Get Subscription Status
     *
     * @return array
     */
    public function getSubscriptionStatus()
    {
        return $this->getProduct()->getSubscriptionStatus();
    }

    /**
     * Get Subscription All Data
     *
     * @return array
     */
    public function getSubscriptionData():array
    {
        $subData = [];
        $product  = $this->getProduct();
        $attr = $product->getResource()->getAttribute('subscription_type');
        $optionValue = [];
        $atrr = $product->getResource()->getAttribute('subscription_discount_type');
            $type = $atrr->getSource()->getOptionText($product->getSubscriptionDiscountType());
        
        if ($product->getSubscriptionType() != null) {
            foreach (explode(',', $product->getSubscriptionType()) as $option) {
                $optionValue[strtolower($attr->getSource()->getOptionText($option))]=
                $attr->getSource()->getOptionText($option);
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

        return $subData;
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

            if ($type == self::FIXED_AMOUNT) {
                return $product->getSubscriptionDiscountValue();
            } elseif ($type == self::PERCENTAGE_PRICE) {
                $value = ($product->getFinalPrice() /100) * $product->getSubscriptionDiscountValue();
                return $product->getFinalPrice() - $value;
            } else {
                return self::NOT_ACTIVE;
            }
        }
    }
}
