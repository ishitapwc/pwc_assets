<?php

/**
 * PwC India
 *
 * @category Magento
 * @package Ecomm_Subscription
 * @author PwC India
 * @license GNU General Public License ("GPL") v3.0
 */
declare(strict_types=1);
namespace Ecomm\Subscription\Model;

/**
 * Description SubscriptionCron Table AbstractModel
 */
class ProductRunner
{
    /**
     * Active Subscription.
     *
     * @param Product $product
     *
     * @return boolean
     */
    public function productValidation($product):bool
    {
        if (!$product->getSubscriptionStatus()) {
            return false;
        } elseif ($product->getSubscriptionDiscount() == null
        || $product->getSubscriptionDiscountValue() == null) {
            return false;
        }

        return true;
    }

    /**
     * Active Subscription.
     *
     * @param Product $product
     *
     * @return array
     */
    public function productDiscount($product):array
    {
        $discount = [];
        // $atrr = $product->getResource()->getAttribute('subscription_discount_type');
        // $type = $atrr->getSource()->getOptionText($product->getSubscriptionDiscountType());
        $discount['type'] = $product->getSubscriptionDiscountType();
        $discount['value'] = $product->getSubscriptionDiscountValue();
        return $discount;
    }
}
