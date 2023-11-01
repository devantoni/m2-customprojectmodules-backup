<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Bdollarapps\ReportSystem\Block\Report;
use MageMe\WebForms\Model\ResourceModel\Field as FieldResource;
use MageMe\WebForms\Api\Data\FieldInterface;
class Assetsummary extends \Bdollarapps\ReportSystem\Block\Report\Availablesssets
{

    const ALLOWED_FIELD_CODES = [
        'rvp_agent_id',
        'self_what_is_the_approximate_balance_on_your_roth_ira',
        'spouse_what_is_the_approximate_balance_on_your_roth_ira',
        'self_approximate_balance_retirement_account_held_with_previous_employer',
        'spouse_approximate_balance_retirement_account_held_with_previous_employer',
        'self_what_is_the_approximate_balance_of_your_savings_and_or_checking_accounts',
        'self_what_is_the_approximate_balance_on_your_annuity',
        'spouse_what_is_the_approximate_balance_on_your_annuity',
        'what_is_the_approximate_expected_amount_of_the_inheritance',
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

    public function getAgentWiseData()
    {

        if(!is_object($this->_collection))
        {
            $collection = $this->getSurveyCollection();
        }else{
           $collection = clone $this->_collection;
        }
        
        $collection->getSelect()->where('results_values_82.value !=?',['neq'=>''])->columns(['tot_ira_self' => new \Zend_Db_Expr('SUM(results_values_130.value)'),'tot_ira_spouse' => new \Zend_Db_Expr('SUM(results_values_137.value)'),'tot_rollover_self' => new \Zend_Db_Expr('SUM(results_values_87.value)'),'tot_rollover_spouse' => new \Zend_Db_Expr('SUM(results_values_116.value)'),'tot_savings' => new \Zend_Db_Expr('SUM(results_values_145.value)'),'tot_annuity_self' => new \Zend_Db_Expr('SUM(results_values_137.value)'),'tot_annuity_spouse' => new \Zend_Db_Expr('SUM(results_values_138.value)'),'tot_inheritance' => new \Zend_Db_Expr('SUM(results_values_156.value)')])->group('results_values_82.value');
    
        return $collection;
    }

    public function getRandomColorHash($key = 0) {

        $colors = ['#28C76F','#EA5455','#7367F0','#B8C2CC','#FF9F43','#00CFE8','#1E1E1E'];
        $color = $colors[$key%7];
        return $color;
    }
}


