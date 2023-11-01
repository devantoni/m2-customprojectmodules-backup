<?php
namespace Bdollarapps\MagemeForms\Model\ResourceModel;

class TokeniseLink extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context
    )
    {
        parent::__construct($context);
    }

    protected function _construct()
    {
        $this->_init('bdollarapps_tokenise_link', 'entity_id');
    }
}
