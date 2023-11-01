<?php
namespace Bdollarapps\MagemeForms\Model\ResourceModel\TokeniseLink;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'entity_id';
	protected $_eventPrefix = 'bdollarapps_magemeforms_tokenise_link_collection';
	protected $_eventObject = 'tokenise_link_collection';

    /**
     * Define the resource model & the model.
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Bdollarapps\MagemeForms\Model\TokeniseLink', 'Bdollarapps\MagemeForms\Model\ResourceModel\TokeniseLink');
    }
}
