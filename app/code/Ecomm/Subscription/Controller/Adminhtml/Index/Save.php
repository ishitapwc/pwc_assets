<?php 

namespace Ecomm\Subscription\Controller\Adminhtml\Index;
//use Magento\Backend\App\Action;
use Magento\Backend\Model\Session;
use Ecomm\Subscription\Model\SubscriptionCron;
use Ecomm\Subscription\Model\SubscriptionCronFactory;
use Ecomm\Subscription\Api\SubscriptionCronRepositoryInterface;
use Ecomm\Subscription\Model\ResourceModel\SubscriptionCron\CollectionFactory;
use Magento\Framework\View\Result\PageFactory;

class Save extends \Magento\Framework\App\Action\Action
{

    protected $uiExamplemodel;
    protected $_b2bOrderrepository;
    protected $obj;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        SubscriptionCron $uiExamplemodel,
        Session $adminsession,
        SubscriptionCronRepositoryInterface $B2bOrderRepository

    ) {
        parent::__construct($context);

        $this->uiExamplemodel = $uiExamplemodel;
        $this->adminsession = $adminsession;
        $this->_b2bOrderrepository = $B2bOrderRepository;
    }  
    
    public function execute()
    {

        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();

        //print_r($data);die;
        
        if ($data) {
            $subscription_id = $this->getRequest()->getParam('entity_id');

            //var_dump($B2bOrder_id);die;
            if ($subscription_id) {
                $this->uiExamplemodel->load($subscription_id);
            }
            $this->uiExamplemodel->setData($data);
            try {
                
                $this->uiExamplemodel->save();
                $this->messageManager->addSuccess(__('The data has been saved.'));
                $this->adminsession->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    if ($this->getRequest()->getParam('back') == 'add') {
                        return $resultRedirect->setPath('*/*/add');
                    } else {
                        return $resultRedirect->setPath('*/*/edit', ['id' => $this->uiExamplemodel->getEntityId(), '_current' => true]);
                    }
                }
                return $resultRedirect->setPath('*/*/');
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the data.'));
            }
            $this->_getSession()->setFormData($data);
            return $resultRedirect->setPath('*/*/edit', ['entity_id' => $this->getRequest()->getParam('entity_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }


    public function execute_old()
    {
        // $data = $this->getRequest()->getPostValue();

        // //echo "<pre>";print_r($data);die;
        // $objectManager = \Magento\Framework\App\ObjectManager::getInstance();       
        // $model = $objectManager->create('Ecomm\Subscription\Model\B2bOrder');
        // //$model->setProductName($this->getRequest()->getParam('product_name'));
        // //$model->setSalesOrderId($this->getRequest()->getParam('sales_order_id'));
        // $model->setData($data);
        // $model->save();
        // $this->messageManager->addSuccess( __('Data has been saved!.') );
        // $this->_redirect('*/*/');
        // return;

        $data = $this->getRequest()->getPostValue();
        $resultRedirect = $this->resultRedirectFactory->create();

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();       
        $model = $objectManager->create('Ecomm\Subscription\Model\SubscriptionCron');

        if ($data) 
        {
            $id = $this->getRequest()->getParam('entity_id');
            
            if($id == '' || $id == null)
            {
                $id = 0;
            }
        
            //$model = $objectManager->create('Ecomm\Subscription\Model\B2bOrder')->load($id);

    
            if (!$model->getEntityId() && $id) 
            {
                $this->messageManager->addErrorMessage(__('Error is there.'));
                return $resultRedirect->setPath('*/*/');
            }
        
            $model->setData($data);
        
            try {

                    $model->save();
                    $this->messageManager->addSuccessMessage(__('The data has been saved.'));
            
                    if ($this->getRequest()->getParam('back')) 
                    {
                        return $resultRedirect->setPath('*/*/edit', ['entity_id' => $model->getEntityId()]);
                    }
                    return $resultRedirect->setPath('*/*/');
                } 
                catch (LocalizedException $e)
                {
                    $this->messageManager->addErrorMessage($e->getMessage());
                } 
                catch (\Exception $e)
                {
                    $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the Note.'));
                }
        
            return $resultRedirect->setPath('*/*/edit', ['entity_id' => $this->getRequest()->getParam('entity_id')]);
        }
        return $resultRedirect->setPath('*/*/');

    }
}

?>