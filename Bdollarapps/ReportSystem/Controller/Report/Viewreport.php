<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Bdollarapps\ReportSystem\Controller\Report;

use Magento\Framework\Controller\ResultFactory;
use MageMe\WebForms\Api\ResultRepositoryInterface;
use Magento\Customer\Model\ResourceModel\CustomerRepository;
use MageMe\WebForms\Api\FormRepositoryInterface;
class Viewreport extends \Magento\Framework\App\Action\Action
{
     /**
     * @var \Magento\Framework\App\Action\Contex
     */
    private $context;

    /**
     * @param \Magento\Framework\App\Action\Context $context
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        ResultRepositoryInterface $resultRepository,
        FormRepositoryInterface $formRepository,
        CustomerRepository $customerRepository,
        \MageMe\WebForms\Model\ResourceModel\Field\CollectionFactory $collectionField
    ) {
        parent::__construct($context);
        $this->context           = $context;
        $this->resultRepository = $resultRepository;
        $this->collectionField = $collectionField;
        $this->formRepository = $formRepository;
        $this->customerRepository = $customerRepository;
    }
    
    /**
     * @return json
     */
    public function execute()
    {
        $id = $this->context->getRequest()->getParam('unique_survey_id');
        $form = $this->formRepository->getById(5);
        $result   = $this->resultRepository->getById((int)$id);
        $data = $result->getData();
        $agentId = $data['field_82'];
        $agentName = $this->getCustomerName($agentId);
        $fields      = $form->getFields();
        $type = [];
        foreach($fields as $_field)
        {
            $type[$_field->getId()] = $_field->getType();
        }
        $resultsData = $this->resultRepository->getResultData($result, $fields);
        $resultsData['agent'] = $agentName;
        $alerts = $this->getAlerts($resultsData['fields'],$type);
        $resultsData['alerts'] = $alerts;
        $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        $resultJson->setData($resultsData);
        return $resultJson;
    }



     public function getCustomerName($id)
      {
        if ($id) {
            try {
                $customer = $this->customerRepository->getById($id);
                return $customer->getFirstname() . ' . ' . $customer->getLastname();  
            } catch (\Exception $e) {
                return 'N/A';
            }
        }
      }


     public function getAlerts($newdata,$type) {
        $alerts = [];
        $debts = 0;
        $data = [];
        foreach ($newdata as $key => $value) {
            $value['value'] = trim($value['value']);
            $typeData = $type[$value['field_id']];
            if($typeData=='number' && $value['value']=='')
            {
                $value['value']=0;
            }
            if($typeData=='text' && $value['value']=='')
            {
               if($value['code']=='self_how_much_coverage_do_you_have_with_your_insurance_with_the_savings_account')
               {
                    $value['value'] = 0;
               }
               if($value['code']=='self_how_much_coverage_do_you_have_with_your_group_insurance')
               {
                    $value['value'] = 0;
               }
               if($value['code']=='self_how_much_coverage_do_you_have_with_your_group_insurance')
               {
                    $value['value'] = 0;
               }
               if($value['code']=='spouse_how_much_coverage_do_you_have_with_your_group_insurance')
               {
                    $value['value'] = 0;
               }
               if($value['code']=='self_how_much_do_you_contribute_annually_to_your_roth_ira')
               {
                    $value['value'] = 0;
               }
               if($value['code']=='self_how_much_do_you_contribute_annually_to_your_traditional_ira')
               {
                    $value['value'] = 0;
               }
            }
            if(is_string($value['value']))
            {
                if(trim($value['value'])=='Yes')
                {
                    $value['value']='yes';
                }
                if(trim($value['value'])=='No')
                {
                    $value['value']='no';
                }
            }
            
            if($typeData=='select_radio')
            {
                $value['value']=strtolower($value['value']);
            }
            $data[$value['code']] = is_string($value['value'])?trim($value['value']):$value['value'];

        }
        
        foreach ($data as $key => $value) {
            if(empty($value))
            {
                continue;
            }
            if($key=='debts')
            {
                if(strpos($value,'Mortgage') !== false)
                {
                    $data['what_household_debts_do_you_have_mortgage']=1;
                }
                if(strpos($value,'Credit Card (2)') !== false)
                {
                    $data['what_household_debts_do_you_have_credit_card_2']=1;
                }
                if(strpos($value,'Car Loan (1)') !== false)
                {
                    $data['what_household_debts_do_you_have_car_load_1']=1;
                }
                if(strpos($value,'Student Loan (1)') !== false)
                {
                    $data['what_household_debts_do_you_have_student_loan_1']=1;
                }
                if(strpos($value,'Personal Loan') !== false)
                {
                    $data['what_household_debts_do_you_have_personal_loan']=1;
                }
                if(strpos($value,'Department Store (2)') !== false)
                {
                    $data['what_household_debts_do_you_have_department_store_2']=1;
                }
                if(strpos($value,'Credit Card (1)') !== false)
                {
                    $data['what_household_debts_do_you_have_credit_card_1']=1;
                }
                if(strpos($value,'Credit Card (3)') !== false)
                {
                    $data['what_household_debts_do_you_have_credit_card_3']=1;
                }
                if(strpos($value,'Car Loan (2)') !== false)
                {
                    $data['what_household_debts_do_you_have_car_load_2']=1;
                }
                if(strpos($value,'Department Store (1)') !== false)
                {
                    $data['what_household_debts_do_you_have_department_store_1']=1;
                }
                if(strpos('Other',$value) !== false)
                {
                    $data['what_household_debts_do_you_have_other']=1;
                }
            }
            if($key=='college_savings_plan_for_children')
            {
                if(strpos($value,'None') !== false)
                {
                    $data['college_savings_plan_for_children_none']=1;
                }
            }
            if($key=='college_savings_plan_for_children')
            {
                if(strpos($value,'None') !== false)
                {
                    $data['college_savings_plan_for_children_none']=1;
                }
            }
            if($key=='spouse_retirement_plan_do_you_have_with_your_current_employer')
            {
                if(strpos($value,'401') !== false)
                {
                    $data['spouse_retirement_plan_do_you_have_with_your_current_employer']='401k';
                }
            }
            if($key=='self_retirement_plan_do_you_have_with_your_current_employer')
            {
                if(strpos($value,'401') !== false)
                {
                    $data['self_retirement_plan_do_you_have_with_your_current_employer']='401k';
                }
            }
            if($key=='self_what_type_of_life_insurance_do_you_have')
            {
                if(strpos($value,'Term Life') !== false)
                {
                    $data['self_what_type_of_life_insurance_do_you_have_term_life']=1;
                }
                if(strpos($value,'Group Life') !== false)
                {
                    $data['self_what_type_of_life_insurance_do_you_have_group_life']=1;
                }
                if(strpos($value,'Insurance with a Savings or Investment Account') !== false)
                {
                    $data['self_what_type_of_life_insurance_do_you_have_insurance_with_savings_or_investment_account']=1;
                }
                if(strpos($value,'None') !== false)
                {
                    $data['self_what_type_of_life_insurance_do_you_have_none']=1;
                }
            }
             if($key=='spouse_what_type_of_life_insurance_do_you_have')
            {
                if(strpos($value,'Term Life') !== false)
                {
                    $data['spouse_what_type_of_life_insurance_do_you_have_term_life']=1;
                }
                if(strpos($value,'Group Life') !== false)
                {
                    $data['spouse_what_type_of_life_insurance_do_you_have_group_life']=1;
                }
                if(strpos($value,'Insurance with a Savings or Investment Account') !== false)
                {
                    $data['spouse_what_type_of_life_insurance_do_you_have_insurance_with_savings_or_investment_account']=1;
                }
                if(strpos($value,'None') !== false)
                {
                    $data['spouse_what_type_of_life_insurance_do_you_have_none']=1;
                }
            }
            if($key=='spouse_retirement_plans_for_you_or_your_employees')
            {
                if(strpos($value,'401') !== false)
                {
                    $data['spouse_retirement_plans_for_you_or_your_employees_401k']=1;
                }
                if(strpos($value,'None') !== false)
                {
                    $data['spouse_retirement_plans_for_you_or_your_employees_none']=1;
                }
            }
            if($key=='self_retirement_plans_for_you_or_your_employees')
            {
                if(strpos($value,'401') !== false)
                {
                    $data['self_retirement_plans_for_you_or_your_employees_401k']=1;
                }
                if(strpos($value, 'None') !== false)
                {
                    $data['self_retirement_plans_for_you_or_your_employees_none']=1;
                }
            }
            if($key=='self_what_other_investments_do_you_have_roth')
            {
                if(strpos($value, 'Roth IRA') !== false)
                {
                    $data['self_what_other_investments_do_you_have_roth_ira']=1;
                }
                if(strpos($value,'Traditional IRA') !== false)
                {
                    $data['self_what_other_investments_do_you_have_traditional_ira']=1;
                }
            }
            if($key=='spouse_what_other_investments_do_you_have')
            {
                if(strpos($value,'Roth IRA') !== false)
                {
                    $data['spouse_what_other_investments_do_you_have_roth_ira']=1;
                }
                if(strpos($value,'Traditional IRA') !== false)
                {
                    $data['spouse_what_other_investments_do_you_have_traditional_ira']=1;
                }
            }
            if($key=='self_employee')
            {
    
                if(strpos($value,'Business owner') !== false)
                {
                    $data['self_employed_bussiness_owner']=1;
                }
            }
            if($key=='self_employee')
            {
                if(strpos($value,'Business owner') !== false)
                {
                    $data['spouse_employed_bussiness_owner']=1;
                }
            }

        }
        
        $debts = isset($data['what_household_debts_do_you_have_mortgage']) && $data['what_household_debts_do_you_have_mortgage'] == 1 ? ($debts + 1) : $debts;
        $debts = isset($data['what_household_debts_do_you_have_credit_card_1']) && $data['what_household_debts_do_you_have_credit_card_1'] == 1 ? ($debts + 1) : $debts;
        $debts = isset($data['what_household_debts_do_you_have_credit_card_2']) && $data['what_household_debts_do_you_have_credit_card_2'] == 1 ? ($debts + 1) : $debts;
        $debts = isset($data['what_household_debts_do_you_have_credit_card_3']) && $data['what_household_debts_do_you_have_credit_card_3'] == 1 ? ($debts + 1) : $debts;
        $debts = isset($data['what_household_debts_do_you_have_car_load_1']) && $data['what_household_debts_do_you_have_car_load_1'] == 1 ? ($debts + 1) : $debts;
        $debts = isset($data['what_household_debts_do_you_have_car_load_2']) && $data['what_household_debts_do_you_have_car_load_2'] == 1 ? ($debts + 1) : $debts;
        $debts = isset($data['what_household_debts_do_you_have_student_loan_1']) && $data['what_household_debts_do_you_have_student_loan_1'] == 1 ? ($debts + 1) : $debts;
        $debts = isset($data['what_household_debts_do_you_have_student_loan_2']) && $data['what_household_debts_do_you_have_student_loan_2'] == 1 ? ($debts + 1) : $debts;
        $debts = isset($data['what_household_debts_do_you_have_personal_loan']) && $data['what_household_debts_do_you_have_personal_loan'] == 1 ? ($debts + 1) : $debts;
        $debts = isset($data['what_household_debts_do_you_have_department_store_1']) && $data['what_household_debts_do_you_have_department_store_1'] == 1 ? ($debts + 1) : $debts;
        $debts = isset($data['what_household_debts_do_you_have_department_store_2']) && $data['what_household_debts_do_you_have_department_store_2'] == 1 ? ($debts + 1) : $debts;
        $debts = isset($data['what_household_debts_do_you_have_other']) && $data['what_household_debts_do_you_have_other'] == 1 ? ($debts + 1) : $debts;
        
        $data['what_is_your_average_annual_household_tax_refund'] = isset($data['what_is_your_average_annual_household_tax_refund']) ? str_replace(",", "", $data['what_is_your_average_annual_household_tax_refund']) : 0;

        $data['spouse_what_is_the_approximate_balance_of_your_savings_and_or_checking_accounts'] = isset($data['spouse_what_is_the_approximate_balance_of_your_savings_and_or_checking_accounts']) ? $this->makeNumberFormat($data['spouse_what_is_the_approximate_balance_of_your_savings_and_or_checking_accounts']) : 0;

        $data['self_what_is_the_approximate_balance_of_your_savings_and_or_checking_accounts'] = isset($data['self_what_is_the_approximate_balance_of_your_savings_and_or_checking_accounts']) ? $this->makeNumberFormat($data['self_what_is_the_approximate_balance_of_your_savings_and_or_checking_accounts']) : 0;

        $data['spouse_how_much_do_you_contribute_annually_to_your_traditional_ira'] = isset($data['spouse_how_much_do_you_contribute_annually_to_your_traditional_ira']) ? $this->makeNumberFormat($data['spouse_how_much_do_you_contribute_annually_to_your_traditional_ira']) : 0;

        $data['self_how_much_do_you_contribute_annually_to_your_traditional_ira'] = isset($data['self_how_much_do_you_contribute_annually_to_your_traditional_ira']) ? $this->makeNumberFormat($data['self_how_much_do_you_contribute_annually_to_your_traditional_ira']) : 0;

        $data['spouse_what_is_the_approximate_balance_on_your_traditional_ira'] = isset($data['spouse_what_is_the_approximate_balance_on_your_traditional_ira']) ? $this->makeNumberFormat($data['spouse_what_is_the_approximate_balance_on_your_traditional_ira']) : 0;

        $data['self_what_is_the_approximate_balance_on_your_traditional_ira'] = isset($data['self_what_is_the_approximate_balance_on_your_traditional_ira']) ? $this->makeNumberFormat($data['self_what_is_the_approximate_balance_on_your_traditional_ira']) : 0;

        $data['spouse_what_is_the_approximate_balance_on_your_roth_ira'] = isset($data['spouse_what_is_the_approximate_balance_on_your_roth_ira']) ? $this->makeNumberFormat($data['spouse_what_is_the_approximate_balance_on_your_roth_ira']) : 0;

        $data['self_what_is_the_approximate_balance_on_your_roth_ira'] = isset($data['self_what_is_the_approximate_balance_on_your_roth_ira']) ? $this->makeNumberFormat($data['self_what_is_the_approximate_balance_on_your_roth_ira']) : 0;

        $data['spouse_how_much_coverage_do_you_have_with_your_insurance_with_the_savings_account'] = isset($data['spouse_how_much_coverage_do_you_have_with_your_insurance_with_the_savings_account']) ? $this->makeNumberFormat($data['spouse_how_much_coverage_do_you_have_with_your_insurance_with_the_savings_account']) : 0;

        $data['self_how_much_coverage_do_you_have_with_your_insurance_with_the_savings_account'] = isset($data['self_how_much_coverage_do_you_have_with_your_insurance_with_the_savings_account']) ? $this->makeNumberFormat($data['self_how_much_coverage_do_you_have_with_your_insurance_with_the_savings_account']) : 0;

        $data['spouse_how_much_coverage_do_you_have_with_your_group_insurance'] = isset($data['spouse_how_much_coverage_do_you_have_with_your_group_insurance']) ? $this->makeNumberFormat($data['spouse_how_much_coverage_do_you_have_with_your_group_insurance']) : 0;

        $data['self_how_much_coverage_do_you_have_with_your_group_insurance'] = isset($data['self_how_much_coverage_do_you_have_with_your_group_insurance']) ? $this->makeNumberFormat($data['self_how_much_coverage_do_you_have_with_your_group_insurance']) : 0;

        $data['spouse_how_much_coverage_do_you_have_with_your_Term_Insurance'] = isset($data['spouse_how_much_coverage_do_you_have_with_your_Term_Insurance']) ? $this->makeNumberFormat($data['spouse_how_much_coverage_do_you_have_with_your_Term_Insurance']) : 0;

        $data['self_how_much_coverage_do_you_have_with_your_Term_Insurance'] = isset($data['self_how_much_coverage_do_you_have_with_your_Term_Insurance']) ? $this->makeNumberFormat($data['self_how_much_coverage_do_you_have_with_your_Term_Insurance']) : 0;

        $data['self_how_much_do_you_contribute_annually_to_your_roth_ira'] = isset($data['self_how_much_do_you_contribute_annually_to_your_roth_ira']) ? $this->makeNumberFormat($data['self_how_much_do_you_contribute_annually_to_your_roth_ira']) : 0;

        $data['self_independent_contractors_work_for_you'] = isset($data['self_independent_contractors_work_for_you']) ? $this->makeNumberFormat($data['self_independent_contractors_work_for_you']) : 0;

        $data['spouse_how_much_do_you_contribute_annually_to_your_roth_ira'] = isset($data['spouse_how_much_do_you_contribute_annually_to_your_roth_ira']) ? $this->makeNumberFormat($data['spouse_how_much_do_you_contribute_annually_to_your_roth_ira']) : 0;

        $data['spouse_annual_income_before_taxes'] = isset($data['spouse_annual_income_before_taxes']) ? $this->makeNumberFormat($data['spouse_annual_income_before_taxes']) : 0;

       

        $data['self_annual_income_before_taxes'] = isset($data['self_annual_income_before_taxes']) ? $this->makeNumberFormat($data['self_annual_income_before_taxes']) : 0;

        $data['self_total_insurance'] = $data['self_how_much_coverage_do_you_have_with_your_Term_Insurance'] + $data['self_how_much_coverage_do_you_have_with_your_group_insurance'] + $data['self_how_much_coverage_do_you_have_with_your_insurance_with_the_savings_account'];

        $data['self_annual_income'] = $data['self_annual_income_before_taxes'] * 10;

        $alerts['you_may_be_underinsured'] = $data['self_total_insurance'] < $data['self_annual_income'] ? 1 : 0 ;

        $data['spouse_annual_income']   = isset($data['spouse_annual_income_before_taxes']) && $data['spouse_annual_income_before_taxes'] != '' ? $data['spouse_annual_income_before_taxes'] * 10 : 0;
        $data['spouse_total_insurance'] = 0;

        if ($data['spouse_annual_income'] != 0) {
            $data['spouse_total_insurance'] = (int)$data['spouse_how_much_coverage_do_you_have_with_your_Term_Insurance'] + (int)$data['spouse_how_much_coverage_do_you_have_with_your_group_insurance'] + (int)$data['spouse_how_much_coverage_do_you_have_with_your_insurance_with_the_savings_account'];
        }

        $data['self_annually_contribution_to_your_roth_ira'] = '';
        if (isset($data['self_what_other_investments_do_you_have_roth_ira']) &&
            $data['self_what_other_investments_do_you_have_roth_ira'] == 1 &&
            isset($data['self_what_other_investments_do_you_have_traditional_ira']) &&
            $data['self_what_other_investments_do_you_have_traditional_ira'] == 1 ) {
            if($data['self_annually_contribution_to_your_roth_ira']=='')
            {
                $data['self_annually_contribution_to_your_roth_ira']=0;
            }
            if($data['self_how_much_do_you_contribute_annually_to_your_roth_ira']=='')
            {
                $data['self_what_is_the_approximate_balance_on_your_traditional_ira']=0;
            }
            $data['self_annually_contribution_to_your_roth_ira'] = $data['self_how_much_do_you_contribute_annually_to_your_roth_ira'] + $data['self_what_is_the_approximate_balance_on_your_traditional_ira'];
        }

        $data['spouse_annually_contribution_to_your_roth_ira'] = '';
        if (isset($data['spouse_what_other_investments_do_you_have_roth_ira']) &&
            $data['spouse_what_other_investments_do_you_have_roth_ira'] == 1 &&
            isset($data['spouse_what_other_investments_do_you_have_traditional_ira']) &&
            $data['spouse_what_other_investments_do_you_have_traditional_ira'] == 1 ) {
            if($data['spouse_how_much_do_you_contribute_annually_to_your_roth_ira']=='')
            {
                $data['spouse_how_much_do_you_contribute_annually_to_your_roth_ira']=0;
            }
            if($data['spouse_what_is_the_approximate_balance_on_your_traditional_ira']=='')
            {
                $data['spouse_what_is_the_approximate_balance_on_your_traditional_ira']=0;
            }
            $data['spouse_annually_contribution_to_your_roth_ira'] = $data['spouse_how_much_do_you_contribute_annually_to_your_roth_ira'] + $data['spouse_what_is_the_approximate_balance_on_your_traditional_ira'];
        }

        $alerts['you_may_be_overfunding_your_roth_ira']       = 0;
        $alerts['you_may_be_underfunding_your_roth_ira']      = 0;
        $alerts['you_may_be_underfunding_your_roth_or_traditional_ira'] = 0;
        $alerts['you_may_be_overfunding_your_roth_or_traditional_ira']  = 0;

        if (isset($data['self_what_other_investments_do_you_have_roth_ira']) &&
            $data['self_what_other_investments_do_you_have_roth_ira'] == 1 &&
            isset($data['self_what_other_investments_do_you_have_traditional_ira']) &&
            $data['self_what_other_investments_do_you_have_traditional_ira'] == 1 &&
            isset($data['self_how_much_do_you_contribute_annually_to_your_roth_ira']) &&
            isset($data['self_how_much_do_you_contribute_annually_to_your_traditional_ira']))
        {
            $contribution = $data['self_how_much_do_you_contribute_annually_to_your_roth_ira'] + $data['self_how_much_do_you_contribute_annually_to_your_traditional_ira'];

            if (isset($data['how_do_you_file_your_taxes']) &&
                $data['how_do_you_file_your_taxes'] == 'married_filing_joindly' &&
                isset($data['self_is_your_household_modified_gross_income_less_than_204000']) &&
                $data['self_is_your_household_modified_gross_income_less_than_204000'] == 'no' &&
                $contribution >= 1)
            {
                $alerts['you_may_be_overfunding_your_roth_or_traditional_ira'] = 1;

            } else if (isset($data['how_do_you_file_your_taxes']) &&
                $data['how_do_you_file_your_taxes'] == 'married_filing_joindly' &&
                isset($data['self_is_your_modified_gross_income_less_than_129000']) &&
                $data['self_is_your_modified_gross_income_less_than_129000'] == 'no' &&
                $contribution >= 1)
            {
                $alerts['you_may_be_overfunding_your_roth_or_traditional_ira'] = 1;

            } else if (isset($data['self_what_is_your_age']) &&
                $data['self_what_is_your_age'] >= 50 &&
                isset($data['self_annual_income_before_taxes']) &&
                $data['self_annual_income_before_taxes'] > 7500 &&
                $contribution > 7500)
            {
                $alerts['you_may_be_overfunding_your_roth_or_traditional_ira'] = 1;

            } else if (isset($data['self_what_is_your_age']) &&
                $data['self_what_is_your_age'] < 50 &&
                isset($data['self_annual_income_before_taxes']) &&
                $data['self_annual_income_before_taxes'] > 6500 &&
                $contribution > 6500) {
                $alerts['you_may_be_overfunding_your_roth_or_traditional_ira'] = 1;

            } else if (isset($data['self_what_is_your_age']) &&
                $data['self_what_is_your_age'] >= 50 &&
                isset($data['self_annual_income_before_taxes']) &&
                $data['self_annual_income_before_taxes'] > 7500 &&
                $contribution < 7500)
            {
                $alerts['you_may_be_underfunding_your_roth_or_traditional_ira'] = 1;

            } else if (isset($data['self_what_is_your_age']) &&
                $data['self_what_is_your_age'] < 50 &&
                isset($data['self_annual_income_before_taxes']) &&
                $data['self_annual_income_before_taxes'] > 6500 &&
                $contribution < 6500)
            {
                $alerts['you_may_be_underfunding_your_roth_or_traditional_ira'] = 1;
            }
        }

        if ($alerts['you_may_be_underfunding_your_roth_or_traditional_ira'] == 0 &&
            $alerts['you_may_be_overfunding_your_roth_or_traditional_ira'] == 0 &&
            isset($data['self_how_much_do_you_contribute_annually_to_your_roth_ira']) &&
            isset($data['self_what_other_investments_do_you_have_roth_ira']) &&
            $data['self_what_other_investments_do_you_have_roth_ira'] == 1)
        {
            if (isset($data['how_do_you_file_your_taxes']) &&
                $data['how_do_you_file_your_taxes'] == 'married_filing_joindly' &&
                isset($data['self_is_your_household_modified_gross_income_less_than_204000']) &&
                $data['self_is_your_household_modified_gross_income_less_than_204000'] == 'no' &&
                $data['self_how_much_do_you_contribute_annually_to_your_roth_ira'] > 0) {
                $alerts['you_may_be_overfunding_your_roth_ira']  = 1;
                $alerts['you_may_be_underfunding_your_roth_ira'] = 0;
            } else if (isset($data['self_is_your_modified_gross_income_less_than_129000']) &&
                $data['self_is_your_modified_gross_income_less_than_129000'] == 'no' &&
                isset($data['self_how_much_do_you_contribute_annually_to_your_roth_ira']) &&
                $data['self_how_much_do_you_contribute_annually_to_your_roth_ira'] > 0) {
                $alerts['you_may_be_overfunding_your_roth_ira']  = 1;
                $alerts['you_may_be_underfunding_your_roth_ira'] = 0;
            } else if (isset($data['self_what_is_your_age']) &&
                (($data['self_how_much_do_you_contribute_annually_to_your_roth_ira'] > 7500 &&
                        $data['self_what_is_your_age'] >= 50) ||
                    ($data['self_how_much_do_you_contribute_annually_to_your_roth_ira'] > 6500 &&
                        $data['self_what_is_your_age'] < 50)))
            {

                $alerts['you_may_be_underfunding_your_roth_ira'] = 0;
                $alerts['you_may_be_overfunding_your_roth_ira']  = 1;
            } else if (isset($data['self_what_is_your_age']) &&
                ($data['self_what_is_your_age'] >= 50 &&
                    $data['self_how_much_do_you_contribute_annually_to_your_roth_ira'] < 7500 &&
                    $data['self_how_much_do_you_contribute_annually_to_your_roth_ira'] > 0) ||
                ($data['self_what_is_your_age'] < 50 &&
                    isset($data['self_how_much_do_you_contribute_annually_to_your_roth_ira']) &&
                    $data['self_how_much_do_you_contribute_annually_to_your_roth_ira'] < 6500 &&
                    $data['self_how_much_do_you_contribute_annually_to_your_roth_ira'] > 0))
            {
                $alerts['you_may_be_underfunding_your_roth_ira'] = 1;
                $alerts['you_may_be_overfunding_your_roth_ira']  = 0;
            }
        }

        $alerts['you_may_be_overfunding_your_traditional_ira']  = 0;
        $alerts['you_may_be_underfunding_your_traditional_ira'] = 0;

        if ($alerts['you_may_be_underfunding_your_roth_or_traditional_ira'] == 0 &&
            $alerts['you_may_be_overfunding_your_roth_or_traditional_ira'] == 0 &&
            isset($data['self_what_other_investments_do_you_have_traditional_ira']) &&
            $data['self_what_other_investments_do_you_have_traditional_ira'] == 1 &&
            isset($data['self_how_much_do_you_contribute_annually_to_your_traditional_ira']))
        {
            if (isset($data['how_do_you_file_your_taxes']) &&
                $data['how_do_you_file_your_taxes'] == 'married_filing_joindly' &&
                $data['self_how_much_do_you_contribute_annually_to_your_traditional_ira'] > 0) {
                $alerts['you_may_be_overfunding_your_traditional_ira']  = 1;
                $alerts['you_may_be_underfunding_your_traditional_ira'] = 0;
            } else if (isset($data['self_what_is_your_age']) &&
                (($data['self_how_much_do_you_contribute_annually_to_your_traditional_ira'] > 7500 &&
                        $data['self_what_is_your_age'] >= 50) ||
                    ($data['self_how_much_do_you_contribute_annually_to_your_traditional_ira'] > 6500 &&
                        $data['self_what_is_your_age'] < 50)))
            {
                $alerts['you_may_be_underfunding_your_traditional_ira']  = 0;
                $alerts['you_may_be_overfunding_your_traditional_ira']   = 1;
            }  else if (isset($data['self_what_is_your_age']) &&
                ($data['self_what_is_your_age'] >= 50 &&
                    $data['self_how_much_do_you_contribute_annually_to_your_traditional_ira'] < 7500 &&
                    $data['self_how_much_do_you_contribute_annually_to_your_traditional_ira'] > 0) ||
                ( $data['self_what_is_your_age'] < 50 &&
                    $data['self_how_much_do_you_contribute_annually_to_your_traditional_ira'] < 6500 &&
                    $data['self_how_much_do_you_contribute_annually_to_your_traditional_ira'] > 0))
            {
                $alerts['you_may_be_underfunding_your_traditional_ira']  = 1;
                $alerts['you_may_be_overfunding_your_traditional_ira']   = 0;
            }
        }

        $alerts['spouse_you_may_be_overfunding_your_roth_ira']       = 0;
        $alerts['spouse_you_may_be_underfunding_your_roth_ira']      = 0;
        $alerts['spouse_you_may_be_underfunding_your_roth_or_traditional_ira'] = 0;
        $alerts['spouse_you_may_be_overfunding_your_roth_or_traditional_ira']  = 0;

        if (isset($data['spouse_what_other_investments_do_you_have_roth_ira']) &&
            $data['spouse_what_other_investments_do_you_have_roth_ira'] == 1 &&
            isset($data['spouse_what_other_investments_do_you_have_traditional_ira']) &&
            $data['spouse_what_other_investments_do_you_have_traditional_ira'] == 1 &&
            isset($data['spouse_how_much_do_you_contribute_annually_to_your_roth_ira']) &&
            isset($data['spouse_how_much_do_you_contribute_annually_to_your_traditional_ira']))
        {
            $contribution = $data['spouse_how_much_do_you_contribute_annually_to_your_roth_ira'] + $data['spouse_how_much_do_you_contribute_annually_to_your_traditional_ira'];

            if (isset($data['how_do_you_file_your_taxes']) &&
                $data['how_do_you_file_your_taxes'] == 'married_filing_joindly' &&
                isset($data['self_is_your_household_modified_gross_income_less_than_204000']) &&
                $data['self_is_your_household_modified_gross_income_less_than_204000'] == 'no' &&
                $contribution >= 1)
            {
                $alerts['spouse_you_may_be_underfunding_your_roth_or_traditional_ira']    = 0;
                $alerts['spouse_you_may_be_overfunding_your_roth_or_traditional_ira']     = 1;
            } else if (isset($data['spouse_what_is_your_age']) &&
                $data['spouse_what_is_your_age'] >= 50 &&
                isset($data['spouse_annual_income_before_taxes']) &&
                ($data['spouse_annual_income_before_taxes'] > 7500 && $contribution > 7500) ||
                ($data['spouse_annual_income_before_taxes'] > 6500 && $contribution > 6500))
            {
                $alerts['spouse_you_may_be_underfunding_your_roth_or_traditional_ira']    = 0;
                $alerts['spouse_you_may_be_overfunding_your_roth_or_traditional_ira']     = 1;
            } else if (isset($data['spouse_what_is_your_age']) &&
                $data['spouse_what_is_your_age'] >= 50 &&
                isset($data['spouse_annual_income_before_taxes']) &&
                ($data['spouse_annual_income_before_taxes'] > 7500 && $contribution < 7500) ||
                ($data['spouse_annual_income_before_taxes'] > 6500 && $contribution < 6500))
            {
                $alerts['spouse_you_may_be_underfunding_your_roth_or_traditional_ira']    = 1;
                $alerts['spouse_you_may_be_overfunding_your_roth_or_traditional_ira']     = 0;
            }
        }

        if ($alerts['spouse_you_may_be_underfunding_your_roth_or_traditional_ira'] == 0 &&
            $alerts['spouse_you_may_be_overfunding_your_roth_or_traditional_ira'] == 0 &&
            isset($data['spouse_what_other_investments_do_you_have_roth_ira']) &&
            $data['spouse_what_other_investments_do_you_have_roth_ira'] == 1 &&
            isset($data['spouse_how_much_do_you_contribute_annually_to_your_roth_ira']))
        {
            if (isset($data['how_do_you_file_your_taxes']) &&
                $data['how_do_you_file_your_taxes'] == 'married_filing_joindly' &&
                isset($data['self_is_your_household_modified_gross_income_less_than_204000']) &&
                $data['self_is_your_household_modified_gross_income_less_than_204000'] == 'no' &&
                $data['spouse_how_much_do_you_contribute_annually_to_your_roth_ira'] > 0)
            {
                $alerts['spouse_you_may_be_overfunding_your_roth_ira']    = 1;
                $alerts['spouse_you_may_be_underfunding_your_roth_ira']   = 0;
            } else if (isset($data['spouse_what_is_your_age']) &&
                (($data['spouse_how_much_do_you_contribute_annually_to_your_roth_ira'] > 7500 &&
                        $data['spouse_what_is_your_age'] >= 50) ||
                    ($data['spouse_how_much_do_you_contribute_annually_to_your_roth_ira'] > 6500 &&
                        $data['spouse_what_is_your_age'] < 50))) {

                $alerts['spouse_you_may_be_underfunding_your_roth_ira']   = 0;
                $alerts['spouse_you_may_be_overfunding_your_roth_ira']    = 1;
            } else if (isset($data['spouse_what_is_your_age']) &&
                ($data['spouse_what_is_your_age'] > 50 &&
                    $data['spouse_how_much_do_you_contribute_annually_to_your_roth_ira'] < 7500 &&
                    $data['spouse_how_much_do_you_contribute_annually_to_your_roth_ira'] > 0) ||
                ($data['spouse_what_is_your_age'] <= 50 &&
                    $data['spouse_how_much_do_you_contribute_annually_to_your_roth_ira'] < 6500 &&
                    $data['spouse_how_much_do_you_contribute_annually_to_your_roth_ira'] > 0))
            {
                $alerts['spouse_you_may_be_underfunding_your_roth_ira']   = 1;
                $alerts['spouse_you_may_be_overfunding_your_roth_ira']    = 0;
            }
        }


        $alerts['spouse_you_may_be_overfunding_your_traditional_ira']     = 0;
        $alerts['spouse_you_may_be_underfunding_your_traditional_ira']    = 0;

        if ($alerts['spouse_you_may_be_underfunding_your_roth_or_traditional_ira'] == 0 &&
            $alerts['spouse_you_may_be_overfunding_your_roth_or_traditional_ira'] == 0 &&
            isset($data['spouse_how_much_do_you_contribute_annually_to_your_traditional_ira']) &&
            isset($data['spouse_what_other_investments_do_you_have_traditional_ira']) &&
            $data['spouse_what_other_investments_do_you_have_traditional_ira'] == 1)
        {
            if (isset($data['how_do_you_file_your_taxes']) &&
                $data['how_do_you_file_your_taxes'] == 'married_filing_joindly' &&
                $data['spouse_how_much_do_you_contribute_annually_to_your_traditional_ira'] > 0)
            {
                $alerts['spouse_you_may_be_overfunding_your_traditional_ira']     = 1;
            } else if (isset($data['spouse_what_is_your_age']) &&
                (($data['spouse_how_much_do_you_contribute_annually_to_your_traditional_ira'] > 7500 &&
                        $data['spouse_what_is_your_age'] >= 50) ||
                    ($data['spouse_how_much_do_you_contribute_annually_to_your_traditional_ira'] > 6500 &&
                        $data['spouse_what_is_your_age'] < 50)))
            {
                $alerts['spouse_you_may_be_overfunding_your_traditional_ira']     = 1;
            } else if (isset($data['spouse_what_is_your_age']) &&
                ($data['spouse_what_is_your_age'] >= 50 &&
                    $data['spouse_how_much_do_you_contribute_annually_to_your_traditional_ira'] < 7500 &&
                    $data['spouse_how_much_do_you_contribute_annually_to_your_traditional_ira'] > 0) ||
                ($data['spouse_what_is_your_age'] < 50 &&
                    $data['spouse_how_much_do_you_contribute_annually_to_your_traditional_ira'] < 6500 &&
                    $data['spouse_how_much_do_you_contribute_annually_to_your_traditional_ira'] > 0))
            {
                $alerts['spouse_you_may_be_underfunding_your_traditional_ira']    = 1;
                $alerts['spouse_you_may_be_overfunding_your_traditional_ira']     = 0;
            }
        }
        

        $alerts['no_will'] = isset($data['self_do_you_have_a_will']) && $data['self_do_you_have_a_will'] == 'no' ? 1 : 0 ;

        $alerts['spouse_no_will'] = isset($data['spouse_do_you_have_a_will']) && $data['spouse_do_you_have_a_will'] == 'no' ? 1 : 0 ;

        $alerts['update_your_will'] = isset($data['self_has_your_will_been_updated_in_the_past_3_years']) && $data['self_has_your_will_been_updated_in_the_past_3_years'] == 'no' ? 1 : 0 ;

        $alerts['spouse_update_your_will'] = isset($data['spouse_has_your_will_been_updated_in_the_past_3_years']) && $data['spouse_has_your_will_been_updated_in_the_past_3_years'] == 'no' ? 1 : 0 ;

        $alerts['no_identity_theft_protection'] = isset($data['self_do_you_have_identity_theft_protection']) && $data['self_do_you_have_identity_theft_protection'] == 'no' ? 1 : 0 ;

        $alerts['spouse_no_identity_theft_protection'] = isset($data['spouse_do_you_have_identity_theft_protection']) && $data['spouse_do_you_have_identity_theft_protection'] == 'no' ? 1 : 0 ;

        $alerts['identity_theft_protection_confirm_best_value'] = isset($data['self_do_you_have_identity_theft_protection']) && $data['self_do_you_have_identity_theft_protection'] == 'yes' ? 1 : 0 ;

        $alerts['spouse_identity_theft_protection_confirm_best_value'] = isset($data['spouse_do_you_have_identity_theft_protection']) && $data['spouse_do_you_have_identity_theft_protection'] == 'yes' ? 1 : 0 ;

        $alerts['self_term_life_insurance_confirm_best_value'] = isset($data['self_what_type_of_life_insurance_do_you_have_term_life']) && $data['self_what_type_of_life_insurance_do_you_have_term_life'] == 1 ? 1 : 0 ;

        $alerts['spouse_term_life_insurance_confirm_best_value'] = isset($data['spouse_what_type_of_life_insurance_do_you_have_term_life']) && $data['spouse_what_type_of_life_insurance_do_you_have_term_life'] == 1 ? 1 : 0 ;

        $alerts['auto_home_comparison'] = isset($data['have_you_compared_prices_on_auto_and_or_home_insurance_in_the_past_year']) && $data['have_you_compared_prices_on_auto_and_or_home_insurance_in_the_past_year'] == 'yes' ? 1 : 0 ;

        
        $alerts['tax_filing_status'] = isset($data['how_do_you_file_your_taxes']) && trim($data['how_do_you_file_your_taxes']) == 'single' && isset($data['dependent_or_parent_living_with_you']) && $data['dependent_or_parent_living_with_you'] == 'yes' && isset($data['do_you_personally_pay_more_than_51_of_the_entire_household_expenses']) && $data['do_you_personally_pay_more_than_51_of_the_entire_household_expenses'] == 'yes' ? 1 : 0 ;

        $alerts['upcoming_retirement_explore_moving_retirement_fund_to_an_ira'] = isset($data['self_retiring_in_12_months']) && $data['self_retiring_in_12_months'] == 'yes' ? 1 : 0 ;

        $alerts['spouse_upcoming_retirement_explore_moving_retirement_fund_to_an_ira'] = isset($data['spouse_retiring_in_12_months']) && $data['spouse_retiring_in_12_months'] == 'yes' ? 1 : 0 ; 

        $alerts['educational_saving_account'] = isset($data['college_savings_plan_for_children_none']) && $data['college_savings_plan_for_children_none'] == 1 && isset($data['do_you_have_children_under_the_age_of_18']) && $data['do_you_have_children_under_the_age_of_18'] == 'yes' ? 1 : 0 ;

        $alerts['no_health_insurance'] = isset($data['self_do_you_have_health_insurance']) && $data['self_do_you_have_health_insurance'] == 'no' ? 1 : 0 ;

        $alerts['spouse_no_health_insurance'] = isset($data['spouse_do_you_have_health_insurance']) && $data['spouse_do_you_have_health_insurance'] == 'no' ? 1 : 0 ;

        $alerts['no_roth_ira'] = 0;
        $data['self_spouse_income'] = $data['spouse_annual_income_before_taxes'] + $data['self_annual_income_before_taxes'];

        if ((!isset($data['self_what_other_investments_do_you_have_roth_ira']) ||
                (isset($data['self_what_other_investments_do_you_have_roth_ira']) &&
                    $data['self_what_other_investments_do_you_have_roth_ira'] != 1)) &&
            (((($data['spouse_annual_income_before_taxes'] == 0 && $data['self_annual_income_before_taxes'] < '129000') ||
                        ($data['spouse_annual_income_before_taxes'] != 0 && $data['self_spouse_income'] < '204000')) ||
                    (isset($data['self_is_your_modified_gross_income_less_than_129000']) &&
                        $data['self_is_your_modified_gross_income_less_than_129000'] == 'yes')) ||
                (isset($data['self_is_your_household_modified_gross_income_less_than_204000']) &&
                    $data['self_is_your_household_modified_gross_income_less_than_204000'] == 'yes')) &&
            isset($data['how_do_you_file_your_taxes']) &&
            $data['how_do_you_file_your_taxes'] != 'married_filing_separately')
        {
            $alerts['no_roth_ira'] = 1;
        }

        $alerts['spouse_no_roth_ira'] = 0;
        if ((!isset($data['spouse_what_other_investments_do_you_have_roth_ira']) ||
                (isset($data['spouse_what_other_investments_do_you_have_roth_ira']) &&
                    $data['spouse_what_other_investments_do_you_have_roth_ira'] != 1)) &&
            (($data['self_spouse_income'] < '218000') ||
                (isset($data['self_is_your_household_modified_gross_income_less_than_204000']) &&
                    $data['self_is_your_household_modified_gross_income_less_than_204000'] == 'yes')) &&
            isset($data['how_do_you_file_your_taxes']) &&
            $data['how_do_you_file_your_taxes'] == 'married_filing_joindly')
        {
            $alerts['spouse_no_roth_ira'] = 1;
        }


        $alerts['roth_ira_confirm_appropriate_allocation'] = isset($data['self_what_other_investments_do_you_have_roth_ira']) && $data['self_what_other_investments_do_you_have_roth_ira'] == 1 && isset($data['self_what_is_the_approximate_balance_on_your_roth_ira']) && $data['self_what_is_the_approximate_balance_on_your_roth_ira'] > '10000' ? 1 : 0 ;

        $alerts['spouse_roth_ira_Confirm_appropriate_allocation'] = isset($data['spouse_what_other_investments_do_you_have_roth_ira']) && $data['spouse_what_other_investments_do_you_have_roth_ira'] == 1 && isset($data['spouse_what_is_the_approximate_balance_on_your_roth_ira']) && $data['spouse_what_is_the_approximate_balance_on_your_roth_ira'] > '10000' ? 1 : 0 ;

        $alerts['traditional_ira_confirm_appropriate_allocation'] = isset($data['self_what_other_investments_do_you_have_traditional_ira']) && $data['self_what_other_investments_do_you_have_traditional_ira'] == 1 && isset($data['self_what_is_the_approximate_balance_on_your_traditional_ira']) && $data['self_what_is_the_approximate_balance_on_your_traditional_ira'] > '10000' ? 1 : 0 ;

        $alerts['spouse_traditional_ira_confirm_appropriate_allocation'] = isset($data['spouse_what_other_investments_do_you_have_traditional_ira']) && $data['spouse_what_other_investments_do_you_have_traditional_ira'] == 1 && isset($data['spouse_what_is_the_approximate_balance_on_your_traditional_ira']) && $data['spouse_what_is_the_approximate_balance_on_your_traditional_ira'] > '10000' ? 1 : 0 ;

        $alerts['consider_a_rollover'] = isset($data['self_retirement_accounts_still_held_with_previous_employer']) && $data['self_retirement_accounts_still_held_with_previous_employer'] == 'yes' ? 1 : 0 ;

        $alerts['spouse_consider_a_rollover'] = isset($data['spouse_retirement_accounts_still_held_with_previous_employer']) && $data['spouse_retirement_accounts_still_held_with_previous_employer'] == 'yes' ? 1 : 0 ;

        $alerts['in_service_distribution'] = isset($data['self_what_is_your_age']) && $data['self_what_is_your_age'] >= 59 && isset($data['self_retirement_plan_do_you_have_with_your_current_employer']) && $data['self_retirement_plan_do_you_have_with_your_current_employer'] == '401k' ? 1 : 0 ;

        $alerts['spouse_in_service_distribution'] = isset($data['spouse_what_is_your_age']) && $data['spouse_what_is_your_age'] >= 59 && isset($data['spouse_retirement_plan_do_you_have_with_your_current_employer']) && $data['spouse_retirement_plan_do_you_have_with_your_current_employer'] == '401k' ? 1 : 0 ;

        $alerts['required_minimum_distributions'] = (isset($data['self_what_is_your_age']) && $data['self_what_is_your_age'] >= 72) || (isset($data['self_what_is_your_age']) && $data['self_what_is_your_age'] >= 71 && isset($data['self_do_you_turn_72_at_any_time_during_this_calendar_year']) && $data['self_do_you_turn_72_at_any_time_during_this_calendar_year'] == 'yes') ? 1 : 0 ;

        $alerts['spouse_required_minimum_distributions'] = (isset($data['spouse_what_is_your_age']) && $data['spouse_what_is_your_age'] >= 72) || (isset($data['spouse_what_is_your_age']) && $data['spouse_what_is_your_age'] >= 71 && isset($data['spouse_do_you_turn_72_at_any_time_during_this_calendar_year']) && $data['spouse_do_you_turn_72_at_any_time_during_this_calendar_year'] == 'yes') ? 1 : 0 ;

        $alerts['upcoming_inheritance'] = isset($data['does_your_household_expect_to_receive_an_inheritance_in_the_next_2_years']) && $data['does_your_household_expect_to_receive_an_inheritance_in_the_next_2_years'] == 'yes' ? 1 : 0 ;

        $alerts['inheritance'] = isset($data['is_your_household_in_the_process_of_receiving_an_inheritance']) && $data['is_your_household_in_the_process_of_receiving_an_inheritance'] == 'yes' ? 1 : 0 ;

        $alerts['tax_refund'] = isset($data['what_is_your_average_annual_household_tax_refund']) && $data['what_is_your_average_annual_household_tax_refund'] > 4000 ? 1 : 0 ;

        $alerts['no_work_sponsored_retirement_plan_in_place'] = isset($data['self_work_sponsored_retirement_plan']) && $data['self_work_sponsored_retirement_plan'] == 'no' ? 1 : 0 ;

        $alerts['spouse_no_work_sponsored_retirement_plan_in_place'] = isset($data['spouse_work_sponsored_retirement_plan']) && $data['spouse_work_sponsored_retirement_plan'] == 'no' ? 1 : 0 ;

        $alerts['401k_not_contributing'] = isset($data['self_are_you_contributing_to_your_401K_plan']) && $data['self_are_you_contributing_to_your_401K_plan'] == 'no' && ((isset($data['self_retirement_plans_for_you_or_your_employees_401k']) && $data['self_retirement_plans_for_you_or_your_employees_401k'] == 1) || (isset($data['self_retirement_plan_do_you_have_with_your_current_employer']) && $data['self_retirement_plan_do_you_have_with_your_current_employer'] == '401k')) ? 1 : 0 ;

        $alerts['spouse_401k_not_contributing'] = isset($data['spouse_are_you_contributing_to_your_401K_plan']) && $data['spouse_are_you_contributing_to_your_401K_plan'] == 'no' && ((isset($data['spouse_retirement_plans_for_you_or_your_employees_401k']) && $data['spouse_retirement_plans_for_you_or_your_employees_401k'] == 1) || (isset($data['spouse_retirement_plan_do_you_have_with_your_current_employer']) && $data['spouse_retirement_plan_do_you_have_with_your_current_employer'] == '401k')) ? 1 : 0 ;

        $alerts['401k_under_contributing_for_employer_match'] = isset($data['self_how_much_are_you_contributing']) && $data['self_how_much_are_you_contributing'] == 'less_than_match' ? 1 : 0 ;

        $alerts['spouse_401k_under_contributing_for_employer_match'] = isset($data['spouse_how_much_are_you_contributing']) && $data['spouse_how_much_are_you_contributing'] == 'less_than_match' ? 1 : 0 ;

        $alerts['explore_the_options_of_a_sep_simple_or_solo_401k_retirement_plan'] = (isset($data['self_employed_bussiness_owner']) && $data['self_employed_bussiness_owner'] == 1) && isset($data['self_how_many_employees_do_you_have']) && $data['self_how_many_employees_do_you_have'] == '0' && isset($data['self_retirement_plans_for_you_or_your_employees_none']) && $data['self_retirement_plans_for_you_or_your_employees_none'] == 1 ? 1 : 0 ;

        $alerts['spouse_explore_the_options_of_a_sep_simple_or_solo_401k_retirement_plan_for_your_spouse'] = (isset($data['spouse_employed_bussiness_owner']) && $data['spouse_employed_bussiness_owner'] == 1) && isset($data['spouse_how_many_employees_do_you_have']) && $data['spouse_how_many_employees_do_you_have'] == '0' && isset($data['spouse_retirement_plans_for_you_or_your_employees_none']) && $data['spouse_retirement_plans_for_you_or_your_employees_none'] == 1 ? 1 : 0 ;

        $alerts['explore_the_options_of_a_simple_retirement_plan'] = (isset($data['self_employed_bussiness_owner']) && $data['self_employed_bussiness_owner'] == 1) && isset($data['self_how_many_employees_do_you_have']) && $data['self_how_many_employees_do_you_have'] == '1-99' && isset($data['self_retirement_plans_for_you_or_your_employees_none']) && $data['self_retirement_plans_for_you_or_your_employees_none'] == 1 ? 1 : 0 ;

        $alerts['spouse_explore_the_options_of_a_simple_retirement_Plan'] = isset($data['spouse_employed_bussiness_owner']) && $data['spouse_employed_bussiness_owner'] == 1 && isset($data['spouse_how_many_employees_do_you_have']) && $data['spouse_how_many_employees_do_you_have'] == '1-99' && isset($data['spouse_retirement_plans_for_you_or_your_employees_none']) && $data['spouse_retirement_plans_for_you_or_your_employees_none'] == 1 ? 1 : 0 ;


        $alerts['explore_the_options_of_a_401k_retirement_plan'] = isset($data['self_employed_bussiness_owner']) && $data['self_employed_bussiness_owner'] == 1 && isset($data['self_how_many_employees_do_you_have']) && $data['self_how_many_employees_do_you_have'] == '100+' && isset($data['self_retirement_plans_for_you_or_your_employees_none']) && $data['self_retirement_plans_for_you_or_your_employees_none'] == 1 ? 1 : 0 ;

        $alerts['Spouse_explore_the_options_of_a_401k_retirement_plan'] = (isset($data['spouse_employed_bussiness_owner']) && $data['spouse_employed_bussiness_owner'] == 1) && isset($data['spouse_how_many_employees_do_you_have']) && $data['spouse_how_many_employees_do_you_have'] == '100+' && isset($data['spouse_retirement_plans_for_you_or_your_employees_none']) && $data['spouse_retirement_plans_for_you_or_your_employees_none'] == 1 ? 1 : 0 ;

        $alerts['fiduciary_you_may_be_personally_legally_liable'] = (isset($data['self_employed_bussiness_owner']) && $data['self_employed_bussiness_owner'] == 1) && isset($data['self_retirement_plans_for_you_or_your_employees_401k']) && $data['self_retirement_plans_for_you_or_your_employees_401k'] == 1 ? 1 : 0 ;

        $alerts['spouse_fiduciary_you_may_be_personally_legally_liable'] = (isset($data['spouse_employed_bussiness_owner']) && $data['spouse_employed_bussiness_owner'] == 1) && isset($data['spouse_retirement_plans_for_you_or_your_employees_401k']) && $data['spouse_retirement_plans_for_you_or_your_employees_401k'] == 1 ? 1 : 0 ;

        $alerts['potentially_paying_high_fees_on_your_401k_plan'] = (isset($data['self_employed_bussiness_owner']) && $data['self_employed_bussiness_owner'] == 1) && isset($data['self_are_the_total_assets_in_your_401K_platform_for_all_employees_in_excess_of_1000000']) && $data['self_are_the_total_assets_in_your_401K_platform_for_all_employees_in_excess_of_1000000'] == 'yes' ? 1 : 0 ;

        $alerts['spouse_potentially_paying_high_fees_on_your_401k_plan'] = (isset($data['spouse_employed_bussiness_owner']) && $data['spouse_employed_bussiness_owner'] == 1) && isset($data['spouse_are_the_total_assets_in_your_401K_platform_for_all_employees_in_excess_of_1000000']) && $data['spouse_are_the_total_assets_in_your_401K_platform_for_all_employees_in_excess_of_1000000'] == 'yes' ? 1 : 0 ;

        $alerts['financial_wellness_seminar_to_increase_401k_participation'] = (isset($data['self_employed_bussiness_owner']) && $data['self_employed_bussiness_owner'] == 1) && isset($data['self_retirement_plans_for_you_or_your_employees_401k']) && $data['self_retirement_plans_for_you_or_your_employees_401k'] == 1 && isset($data['self_your_employee_participation_rate_less_than_50']) && $data['self_your_employee_participation_rate_less_than_50'] == 'yes' ? 1 : 0 ;

        $alerts['spouse_financial_wellness_seminar_to_increase_401k_participation'] = isset($data['spouse_employed_bussiness_owner']) && $data['spouse_employed_bussiness_owner'] == 1 && isset($data['spouse_retirement_plans_for_you_or_your_employees_401k']) && $data['spouse_retirement_plans_for_you_or_your_employees_401k'] == 1 && isset($data['spouse_your_employee_participation_rate_less_than_50']) && $data['spouse_your_employee_participation_rate_less_than_50'] == 'yes' ? 1 : 0 ;

        $alerts['buy_sell_insurance'] = (isset($data['self_employed_bussiness_owner']) && $data['self_employed_bussiness_owner'] == 1) && isset($data['self_business_partners_life_insurance']) && $data['self_business_partners_life_insurance'] == 'no' ? 1 : 0 ;

        $alerts['spouse_buy_sell_insurance'] = (isset($data['spouse_employed_bussiness_owner']) && $data['spouse_employed_bussiness_owner'] == 1) && isset($data['spouse_business_partners_life_insurance']) && $data['spouse_business_partners_life_insurance'] == 'no' ? 1 : 0 ;

        $alerts['consider_debt_consolidation'] = isset($debts) && $debts >= 5 && isset($data['do_you_currently_own_or_rent_your_home']) && $data['do_you_currently_own_or_rent_your_home'] == 'own' ? 1 : 0 ;

        $alerts['new_home_finance'] = isset($data['do_you_plan_on_financing_part_of_this_home_purchase']) && $data['do_you_plan_on_financing_part_of_this_home_purchase'] == 'yes' ? 1 : 0 ;

        $alerts['second_home_finance'] = isset($data['will_you_be_paying_cash_for_that_entire_transaction_or_will_some_of_it_be_financed_as_well']) && $data['will_you_be_paying_cash_for_that_entire_transaction_or_will_some_of_it_be_financed_as_well'] == 'cash' ? 1 : 0 ;

        $alerts['debt_elimination_plan'] = isset($debts) && $debts >= 3 ? 1 : 0 ;

        $alerts['cd_explore_options_with_higher_rate_of_return'] = isset($data['self_is_the_interest_rate_on_your_CD_less_than_2']) && $data['self_is_the_interest_rate_on_your_CD_less_than_2'] == 'yes' ? 1 : 0 ;

        $alerts['spouse_cd_explore_options_with_higher_rate_of_return'] = isset($data['spouse_is_the_interest_rate_on_your_CD_less_than_2']) && $data['spouse_is_the_interest_rate_on_your_CD_less_than_2'] == 'yes' ? 1 : 0 ;

        $alerts['annuities_may_annuitize'] = isset($data['self_what_is_the_approximate_balance_on_your_annuity']) && $data['self_what_is_the_approximate_balance_on_your_annuity'] != '' ? 1 : 0 ;

        $alerts['spouse_annuities_may_annuitize'] = isset($data['spouse_what_is_the_approximate_balance_on_your_annuity']) && $data['spouse_what_is_the_approximate_balance_on_your_annuity'] != '' ? 1 : 0 ;

        $alerts['savings_account'] = isset($data['self_what_is_the_approximate_balance_of_your_savings_and_or_checking_accounts']) && $data['self_what_is_the_approximate_balance_of_your_savings_and_or_checking_accounts'] >= 10000 ? 1 : 0 ;

        $alerts['no_life_insurance'] = isset($data['self_what_type_of_life_insurance_do_you_have_none']) && $data['self_what_type_of_life_insurance_do_you_have_none'] == 1 ? 1 : 0 ;

        if($alerts['no_life_insurance']==1)
        {
            $alerts['you_may_be_underinsured']=0;
        }

        $alerts['spouse_no_life_insurance'] = isset($data['spouse_what_type_of_life_insurance_do_you_have_none']) && $data['spouse_what_type_of_life_insurance_do_you_have_none'] == 1 ? 1 : 0 ;



        $alerts['your_spouse_may_be_underinsured'] = isset($data['spouse_total_insurance']) && isset($data['spouse_annual_income']) && $data['spouse_total_insurance'] != 0 && $data['spouse_total_insurance'] < $data['spouse_annual_income'] ? 1 : 0 ;

        if($alerts['spouse_no_life_insurance']==1)
        {
            $alerts['your_spouse_may_be_underinsured']=0;
        }

        $alerts['insurance_with_saving_account'] = isset($data['self_what_type_of_life_insurance_do_you_have_insurance_with_savings_or_investment_account']) && $data['self_what_type_of_life_insurance_do_you_have_insurance_with_savings_or_investment_account'] == 1 ? 1 : 0 ;

        $alerts['spouse_insurance_with_saving_account'] = isset($data['spouse_what_type_of_life_insurance_do_you_have_insurance_with_savings_or_investment_account']) && $data['spouse_what_type_of_life_insurance_do_you_have_insurance_with_savings_or_investment_account'] == 1 ? 1 : 0 ;

        $alerts['group_life_insurance'] = isset($data['self_what_type_of_life_insurance_do_you_have_group_life']) && $data['self_what_type_of_life_insurance_do_you_have_group_life'] == 1 && (!isset($data['self_what_type_of_life_insurance_do_you_have_term_life']) || $data['self_what_type_of_life_insurance_do_you_have_term_life'] == 0) && (!isset($data['self_what_type_of_life_insurance_do_you_have_insurance_with_savings_or_investment_account']) || $data['self_what_type_of_life_insurance_do_you_have_insurance_with_savings_or_investment_account'] == 0) ? 1 : 0 ;

        $alerts['spouse_group_life_insurance'] = isset($data['spouse_what_type_of_life_insurance_do_you_have_group_life']) && $data['spouse_what_type_of_life_insurance_do_you_have_group_life'] == 1 && (!isset($data['spouse_what_type_of_life_insurance_do_you_have_term_life']) || $data['spouse_what_type_of_life_insurance_do_you_have_term_life'] == 0) && (!isset($data['spouse_what_type_of_life_insurance_do_you_have_insurance_with_savings_or_investment_account']) ||  $data['spouse_what_type_of_life_insurance_do_you_have_insurance_with_savings_or_investment_account'] == 0) ? 1 : 0 ;

        $alerts['beneficiary_update'] = isset($data['are_your_beneficiaries_up_to_date_on_all_your_accounts']) && $data['are_your_beneficiaries_up_to_date_on_all_your_accounts'] == 'no' ? 1 : 0 ;

        $alerts['increase_my_income'] = isset($data['are_you_open_to_additional_strategies_to_increase_your_income']) && $data['are_you_open_to_additional_strategies_to_increase_your_income'] == 'i_would_like_to_increase_my_income_if_possible' ? 1 : 0 ;


        $alerts['how_much_more_money_per_month_would_you_like_to_earn'] = isset($data['how_much_more_money_per_month_would_you_like_to_earn']) ? $data['how_much_more_money_per_month_would_you_like_to_earn'] : 0 ;

        $alerts['smart_home_security_system_confirm_the_best_value'] = isset($data['for_your_home']) && $data['for_your_home'] == 'yes' ? 1 : 0 ;

        $alerts['no_smart_home_security_system'] = isset($data['would_you_like_to_explore_some_options_for_a_smart_home_security_system']) && $data['would_you_like_to_explore_some_options_for_a_smart_home_security_system'] == 'yes' ? 1 : 0 ;

         $alerts['smart_bussiness_security_system_confirm_the_best_value'] = isset($data['for_your_business']) && $data['for_your_business'] == 'yes' ? 1 : 0 ;

        $alerts['no_smart_bussiness_security_system'] = isset($data['would_you_like_to_explore_some_options_for_a_smart_home_security_system']) && $data['would_you_like_to_explore_some_options_for_a_smart_home_security_system'] == 'yes' ? 1 : 0 ;

        return $alerts;
    }

    public function makeNumberFormat($input = 0)
    {
        if($input>0)
            $input = preg_replace('/[^0-9]/', '', $input);

        return $input;
    }
}
