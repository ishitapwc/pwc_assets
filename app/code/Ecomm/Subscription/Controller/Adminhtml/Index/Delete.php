<?php

namespace Ecomm\Subscription\Controller\Adminhtml\Index;
use Magento\Backend\App\Action;

use Magento\Backend\Model\Session;
use Ecomm\Subscription\Model\SubscriptionCron;
use Ecomm\Subscription\Model\SubscriptionCronFactory;
use Ecomm\Subscription\Api\SubscriptionCronRepositoryInterface;
use Ecomm\Subscription\Model\ResourceModel\SubscriptionCron\CollectionFactory;
use Magento\Framework\View\Result\PageFactory;

class Delete extends Action
{
    /**
     * @var \MD\UiExample\Model\Blog
     */
    protected $model;
    /**
     * @param Context                  $context
     * @param \MD\UiExample\Model\Blog $blogFactory
     */
    public function __construct(
       \Magento\Backend\App\Action\Context $context,
       SubscriptionCron $model
    
    ) {
        parent::__construct($context);
        $this->model = $model;
    }
    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Ecomm_Subscription::index_delete');
    }
    /**
     * Delete action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('entity_id');
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($id) {
            try {
                $model = $this->model;
                $model->load($id);
                $model->delete();
                $this->messageManager->addSuccess(__('Record deleted successfully.'));
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
                return $resultRedirect->setPath('*/*/edit', ['id' => $id]);
            }
        }
        $this->messageManager->addError(__('Record does not exist.'));
        return $resultRedirect->setPath('*/*/');
    }
}