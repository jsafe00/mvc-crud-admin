<?php
namespace Thecrud\Note\Model\ResourceModel\TakeNote;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'entity_id';
	protected $_eventPrefix = 'thecrud_take_note_collection';
	protected $_eventObject = 'take_note_collection';

	/**
	 * Define resource model
	 *
	 * @return void
	 */
	protected function _construct()
	{
		$this->_init('Thecrud\Note\Model\TakeNote', 'Thecrud\Note\Model\ResourceModel\TakeNote');
	}

    protected function _initSelect()
    {
        parent::_initSelect();

        // Filter out soft-deleted items
        $this->getSelect()->where('main_table.is_deleted = ?', 0);
    }

}