<?php
namespace Ecomm\Subscription\Model\Config\Source;


class SubType extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{
   /**
    * Get all options
    *
    * @return array
    */
    public function getAllOptions()
    {
        $this->_options = [
                ['label' => __('Daily'), 'value'=>'0'],
                ['label' => __('Alternate Days'), 'value'=>'1'],
                ['label' => __('Weekly'), 'value'=>'2'],
                ['label' => __('Bi-Weekly'), 'value'=>'3'],
                ['label' => __('Monthly'), 'value'=>'4']
            ];
    return $this->_options;
    }
}