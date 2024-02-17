<?php

declare(strict_types=1);

namespace Thecrud\Note\Block\Adminhtml\TakeNote\Edit;

use Magento\Framework\UrlInterface;

class GenericButton
{
    public function __construct(
        UrlInterface $url
    ) {}

    public function getUrl(string $route = '', array $params = []): string
    {
        return $this->url->getUrl($route, $params);
    }
}
