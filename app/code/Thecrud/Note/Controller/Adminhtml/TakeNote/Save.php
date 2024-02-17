<?php

declare(strict_types=1);

namespace Thecrud\Note\Controller\Adminhtml\TakeNote;

use Thecrud\Note\Model\TakeNoteFactory;
use Thecrud\Note\Model\ResourceModel\TakeNote as TakeNoteResource;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException; 

class Save extends Action implements HttpPostActionInterface
{
    public function __construct(
        Context $context,
        TakeNoteResource $resource,
        TakeNoteFactory $takeNoteFactory
    ) {
        parent::__construct($context);
        $this->resource = $resource;
        $this->takeNoteFactory = $takeNoteFactory; 
    }

    public function execute(): ResultInterface
    {
        $data = $this->getRequest()->getPostValue();

        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            $model = $this->takeNoteFactory->create();
            if (empty($data['entity_id'])) {
                $data['entity_id'] = null;
            }

            $model->setData($data);

            try {
                $this->resource->save($model);
                $this->messageManager->addSuccessMessage(__('Saved.'));
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $exception) {
                $this->messageManager->addExceptionMessage($exception);
            } catch (\Throwable $e) {
               $this->messageManager->addErrorMessage(__('Something went wrong while saving.'));
            }
        }

        return $resultRedirect->setPath('*/*/');
    }
}
