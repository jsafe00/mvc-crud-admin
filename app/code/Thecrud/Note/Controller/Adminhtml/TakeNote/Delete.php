<?php

declare(strict_types=1);

namespace Thecrud\Note\Controller\Adminhtml\TakeNote;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;

class Delete extends Action
{
    public $takeNoteFactory;
    
    public function __construct(
        Context $context,
        \Thecrud\Note\Model\TakeNoteFactory $takeNoteFactory
    ) {
        $this->takeNoteFactory = $takeNoteFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $id = $this->getRequest()->getParam('entity_id');
        
        try {
            $takeNoteModel = $this->takeNoteFactory->create();
            $takeNoteModel->load($id);
            
            if ($takeNoteModel->getId()) {
                $takeNoteModel->delete(); 
                $this->messageManager->addSuccessMessage(__('You deleted the note.'));
            } else {
                $this->messageManager->addErrorMessage(__('The note does not exist.'));
            }
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }
        
        return $resultRedirect->setPath('*/*/');
    }

    public function _isAllowed()
    {
        return $this->_authorization->isAllowed('Thecrud_Note::delete');
    }
}