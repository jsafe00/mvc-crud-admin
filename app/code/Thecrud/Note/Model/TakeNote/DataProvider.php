<?php

declare(strict_types=1);

namespace Thecrud\Note\Model\TakeNote;

use Thecrud\Note\Model\TakeNote;
use Thecrud\Note\Model\TakeNoteFactory;
use Thecrud\Note\Model\ResourceModel\TakeNote as TakeNoteResource;
use Thecrud\Note\Model\ResourceModel\TakeNote\CollectionFactory;
use Magento\Framework\App\RequestInterface;
use Magento\Ui\DataProvider\Modifier\PoolInterface;
use Magento\Ui\DataProvider\ModifierPoolDataProvider;

class DataProvider extends ModifierPoolDataProvider
{
    /**
     * @var array
     */
    private array $loadedData;

     /**
     * @var RequestInterface
     */
    private RequestInterface $request;

    /**
     * @var TakeNoteFactory
     */
    private TakeNoteFactory $takeNoteFactory;

    /**
     * @var TakeNoteResource
     */
    private TakeNoteResource $resource;


    /**
     * DataProvider constructor.
     * @param $name
     * @param $primaryFieldName
     * @param $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param TakeNoteResource $resource
     * @param TakeNoteFactory $takeNoteFactory
     * @param RequestInterface $request
     * @param array $meta
     * @param array $data
     * @param PoolInterface|null $pool
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        TakeNoteResource $resource,
        TakeNoteFactory $takeNoteFactory,
        RequestInterface $request,
        array $meta = [],
        array $data = [],
        PoolInterface $pool = null
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data, $pool);
        $this->collection = $collectionFactory->create();
        $this->takeNoteFactory = $takeNoteFactory;
        $this->resource = $resource;
        $this->request = $request;
    }

    /**
     * @return array
     */
    public function getData(): array
    {

        if (isset($this->loadedData)) {
            return $this->loadedData;
        }

        $takeNote = $this->getCurrentTakeNote();
        $this->loadedData[$takeNote->getId()] = $takeNote->getData();

        return $this->loadedData;
    }

    /**
     * @return TakeNote
     */
    private function getCurrentTakeNote(): TakeNote
    {
        $takeNoteId = $this->getTakeNoteId();
        $takeNote = $this->takeNoteFactory->create();
        if (!$takeNoteId) {
            return $takeNote;
        }

        $this->resource->load($takeNote, $takeNoteId);

        return $takeNote;
    }

    /**
     * @return int
     */
    private function getTakeNoteId(): int
    {
        return (int) $this->request->getParam($this->getRequestFieldName());
    }
}
