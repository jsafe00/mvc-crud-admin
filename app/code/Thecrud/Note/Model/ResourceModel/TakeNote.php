<?php
namespace Thecrud\Note\Model\ResourceModel;

class TakeNote extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    public function __construct(
		\Magento\Framework\Model\ResourceModel\Db\Context $context
	)
	{
		parent::__construct($context);
	}
	
	protected function _construct()
	{
		$this->_init('take_note', 'entity_id');
	}

	public function deleteSoft(\Thecrud\Note\Model\TakeNote $model)
    {
        $model->setData('is_deleted', 1);
        $model->setData('deleted_at', date('Y-m-d H:i:s'));
        $this->save($model);
    }

    public function undelete(\Thecrud\Note\Model\TakeNote $model)
    {
        $model->setData('is_deleted', 0);
        $model->setData('deleted_at', null);
        $this->save($model);
    }
}