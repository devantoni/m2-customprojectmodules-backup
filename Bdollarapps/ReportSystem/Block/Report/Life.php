<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Bdollarapps\ReportSystem\Block\Report;

use Bdollarapps\MagemeForms\Helper\Data;
use MageMe\WebForms\Api\Data\FieldInterface;
use MageMe\WebForms\Api\Data\ResultInterface;
use Magento\Framework\App\ResourceConnection;
use MageMe\WebForms\Model\ResourceModel\Field as FieldResource;
use MageMe\WebForms\Model\ResourceModel\ResultValue as ResultValueResource;
use MageMe\WebForms\Api\Data\ResultValueInterface;
use Magento\Customer\Model\ResourceModel\CustomerRepository;

class Life extends \Bdollarapps\ReportSystem\Block\Report\Availablesssets
{
    const ALLOWED_FIELD_CODES = [
        'rvp_agent_id',
        'initials',
        'zip_code',
        'self_what_is_your_age',
        'spouse_what_is_your_age',
        'do_you_have_children_under_the_age_of_18',
        'self_annual_income_before_taxes',
        'spouse_annual_income_before_taxes',
        'what_household_debts_do_you_have_mortgage',
        'self_how_much_coverage_do_you_have_with_your_Term_Insurance',
        'spouse_how_much_coverage_do_you_have_with_your_Term_Insurance',
        'self_what_is_the_approximate_balance_of_your_savings_and_or_checking_accounts',
        'spouse_what_is_the_approximate_balance_of_your_savings_and_or_checking_accounts',
        'self_how_much_coverage_do_you_have_with_your_group_insurance',
        'spouse_how_much_coverage_do_you_have_with_your_group_insurance'
    ];


    protected function getFieldsIds(int $webformId, $collection): array
    {
        $select = $collection->getConnection()->select()
            ->from($collection->getTable(FieldResource::DB_TABLE), [
                FieldInterface::ID, 'code'
            ])
            ->where(FieldInterface::FORM_ID . '= ?', $webformId)
            ->where('code' . ' IN (?)', self::ALLOWED_FIELD_CODES);
        return $collection->getConnection()->fetchAssoc($select);
    }
    
}
