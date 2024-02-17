<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Thecrud\Note\Model\Export;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Exception\BadMethodCallException;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Filesystem;
use Magento\Ui\Component\MassAction\Filter;
use Magento\Ui\Model\Export\MetadataProvider;
use Magento\Framework\View\Element\UiComponentInterface;
use Magento\Ui\Model\Export\ConvertToCsv as ConvertToCsvParent;
use Magento\Framework\Filesystem\Directory\WriteInterface;
use Magento\Framework\Filesystem\Directory\WriteFactory;
use Psr\Log\LoggerInterface;

class ConvertToCsv extends ConvertToCsvParent
{
    protected $filesystem;
    protected $directory;
    protected $metadataProvider;
    protected $pageSize;
    protected $filter;
    protected $logger;

    /**
     * ConvertToCsv constructor.
     *
     * @param Filesystem $filesystem
     * @param Filter $filter
     * @param MetadataProvider $metadataProvider
     * @param WriteFactory $directoryFactory
     * @param int|null $pageSize
     * @param LoggerInterface $logger
     */
    public function __construct(
        Filesystem $filesystem,
        Filter $filter,
        MetadataProvider $metadataProvider,
        WriteFactory $directoryFactory,
        $pageSize = 200, 
        LoggerInterface $logger
    ) {
        $this->filesystem = $filesystem;
        $this->filter = $filter;
        $this->metadataProvider = $metadataProvider;
        $this->directoryFactory = $directoryFactory;
        $this->pageSize = $pageSize;
        $this->logger = $logger;
    }

    /**
     * Returns CSV file
     *
     * @return array
     * @throws LocalizedException
     */
    public function getCsvFile()
    {
        $component = $this->filter->getComponent();
        $name = md5(microtime());
        // md5() here is not for cryptographic use.
        // phpcs:ignore Magento2.Security.InsecureFunction
        $file = 'export/'. $component->getName() . $name . '.csv';

        $this->filter->prepareComponent($component);
        $this->filter->applySelectionOnTargetProvider();
        $dataProvider = $component->getContext()->getDataProvider();
        $fields = $this->metadataProvider->getFields($component);
        $options = $this->metadataProvider->getOptions();
        $this->directory = $this->filesystem->getDirectoryWrite(DirectoryList::VAR_DIR);
        $this->directory->create('export');

       // $this->directory->create('export');
        $stream = $this->directory->openFile($file, 'w+');
        
        $stream->lock();

        $stream->write(chr(0xEF) . chr(0xBB) . chr(0xBF)); // Add UTF-8 BOM

        // Write headers
        $stream->writeCsv($this->metadataProvider->getHeaders($component), ',', '"');
    
        $i = 1;
        $searchCriteria = $dataProvider->getSearchCriteria()
            ->setCurrentPage($i)
            ->setPageSize($this->pageSize);
        $totalCount = (int) $dataProvider->getSearchResult()->getTotalCount();
        while ($totalCount > 0) {
            $items = $dataProvider->getSearchResult()->getItems();
            foreach ($items as $item) {
                $this->metadataProvider->convertDate($item, $component->getName());
    
                // Convert row data to UTF-8
                $rowData = $this->metadataProvider->getRowData($item, $fields, $options);
                foreach ($rowData as &$value) {
                    $value = mb_convert_encoding($value, 'UTF-8');
                }
                $stream->writeCsv($rowData, ',', '"');
            }
            $searchCriteria->setCurrentPage(++$i);
            $totalCount = $totalCount - $this->pageSize;
        }
        $stream->unlock();
        $stream->close();
        $this->logger->info('CSV file generated: ' . $file);
    
        return [
            'type' => 'filename',
            'value' => $file,
            'rm' => true,  // can delete file after use
        ];
    }

}
