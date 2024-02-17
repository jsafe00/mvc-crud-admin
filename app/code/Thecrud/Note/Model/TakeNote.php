<?php
namespace Thecrud\Note\Model;

class TakeNote extends \Magento\Framework\Model\AbstractModel implements \Magento\Framework\DataObject\IdentityInterface
{
    const CACHE_TAG = 'thecrud_notes_take_note';

	protected $_cacheTag = 'thecrud_notes_take_note';

	protected $_eventPrefix = 'thecrud_notes_take_note';

	protected $_idFieldName = 'entity_id';

	protected function _construct()
	{
		$this->_init('Thecrud\Note\Model\ResourceModel\TakeNote');
	}

	public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

	protected function _beforeSave()
    {
        $this->updateTimestamps();
        return parent::_beforeSave();
    }

    public function delete()
    {
        $this->_getResource()->deleteSoft($this);
        return $this;
    }

    public function undelete()
    {
        $this->_getResource()->undelete($this);
        return $this;
    }

    protected function updateTimestamps()
    {
        if ($this->isObjectNew()) {
            $this->setData('created_at', date('Y-m-d H:i:s'));
        }
        $this->setData('updated_at', date('Y-m-d H:i:s'));
        return $this;
    }
}