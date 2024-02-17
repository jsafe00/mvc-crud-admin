<?php

declare(strict_types=1);

namespace Thecrud\Note\Block\Adminhtml\TakeNote\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use Magento\Framework\UrlInterface;

class BackButton extends GenericButton implements ButtonProviderInterface
{
    public $url;

    public function __construct(
        UrlInterface $url
    ) {
        $this->url = $url;
    }

    public function getButtonData(): array
    {
        return [
            'label' => __('Back'),
            'on_click' => sprintf("location.href = '%s'", $this->getBackUrl()),
            'class' => 'back',
            'sort_order' => 10,
        ];
    }

    public function getBackUrl(): string
    {
        return $this->getUrl('*/*/');
    }
}