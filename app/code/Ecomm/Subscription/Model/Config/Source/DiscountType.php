<?php
namespace Ecomm\Subscription\Model\Config\Source;


class DiscountType extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{
   /**
    * Get all options
    *
    * @return array
    */
    public function getAllOptions()
    {
        $this->_options = [
                ['label' => __('Percentage on product Price'), 'value'=>'0'],
                ['label' => __('Fixed Amount'), 'value'=>'1']
            ];
    return $this->_options;
    }
}