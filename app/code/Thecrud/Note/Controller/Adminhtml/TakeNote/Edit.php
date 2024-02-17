<?php

declare(strict_types=1);

namespace Thecrud\Note\Controller\Adminhtml\TakeNote;

use Magento\Backend\App\Action;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\View\Result\Page;

class Edit extends Action implements HttpGetActionInterface
{
    public function execute(): Page
    {
        $pageResult = $this->createPageResult();
        $title = $pageResult->getConfig()->getTitle();
        $title->prepend(__('Note'));
        $title->prepend(__('New Note'));

        return $pageResult;
    }

    private function createPageResult(): Page
    {
        return $this->resultFactory->create(ResultFactory::TYPE_PAGE);
    }
}
