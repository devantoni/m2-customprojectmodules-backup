<?php 

namespace Bdollarapps\ClaueCustomizer\Helper\SellerSubAccount;

class Data extends \Webkul\SellerSubAccount\Helper\Data{

    /**
     * Get Agent Group
     *
     * @return void
     */
    public function getAccountGroup()
    {
        $groupId = 1;
        $coll = $this->_groupCollection
            ->addFieldToFilter('customer_group_code', 'Agent');
        foreach ($coll as $key => $value) {
            if ($value->getCustomerGroupCode() == 'Agent') {
                $groupId = $value->getCustomerGroupId();
            }
        }
        return $groupId;
    }
}