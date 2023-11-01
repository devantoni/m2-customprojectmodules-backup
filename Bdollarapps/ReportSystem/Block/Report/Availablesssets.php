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
use Webkul\SellerSubAccount\Model\SubAccountRepository;
class Availablesssets extends \Magento\Framework\View\Element\Template
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
        'self_what_is_the_approximate_balance_of_your_savings_and_or_checking_accounts',
        'self_approximate_balance_retirement_account_held_with_previous_employer',
        'spouse_approximate_balance_retirement_account_held_with_previous_employer',
        'self_what_is_the_approximate_balance_on_your_roth_ira',
        'spouse_what_is_the_approximate_balance_on_your_roth_ira',
        'self_what_is_the_approximate_balance_on_your_annuity',
        'spouse_what_is_the_approximate_balance_on_your_annuity',
        'what_is_the_approximate_expected_amount_of_the_inheritance'
    ];

    protected $_surveyCollectionFactory;

    protected $userContext;

    protected $_collection;
    protected $_uncollection;

    /**
     * @var CustomerRepository
     */
    protected $customerRepository;

    protected $_customerSession;

    private ResourceConnection $resourceConnection;

    protected $_sellers;

    protected $name;

    /**
     * @var Data
     */
    private $helper;

    /**
     * Constructor
     *
     * @param \Magento\Framework\View\Element\Template\Context  $context
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \MageMe\WebForms\Model\ResourceModel\Result\Grid\CollectionFactory $_surveyCollectionFactory,
        CustomerRepository $customerRepository,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Authorization\Model\UserContextInterface $userContext,
        ResourceConnection $resourceConnection,
        Data $helper,
        SubAccountRepository $subAccountRepository,
        array $data = []
    ) {
        $this->_surveyCollectionFactory = $_surveyCollectionFactory;
        $this->customerRepository = $customerRepository;
        $this->_customerSession = $customerSession;
        $this->userContext = $userContext;
        $this->resourceConnection = $resourceConnection;
        $this->helper = $helper;
        $this->subAccountRepository = $subAccountRepository;
        parent::__construct($context, $data);
    }

    public function getLoggedInStatus(){
        if($this->_customerSession->isLoggedIn()):
            return true;
        endif;
        return false;
    }
    public function getGroupId(){
        if($this->_customerSession->isLoggedIn()):
            return $customerGroup=$this->_customerSession->getCustomer()->getGroupId();
        endif;
        return;
    }
    public function getCustomerId()
    {
        return $this->userContext->getUserId();
    }

    public function getSurveyCollection(){
        $formId = $this->helper->getSurveyFormId()?$this->helper->getSurveyFormId():2;
        $this->getRequest()->setParams(['form_id', (int) $formId]);
        $currentAgent = $this->getRequest()->getParam('agent');
        $month = $this->getRequest()->getParam('month');
        /** @var \MageMe\WebForms\Model\ResourceModel\Result\Grid\Collection $collection */
        if(!$this->_collection){
            $this->_collection = $this->_surveyCollectionFactory->create();
            $this->_collection->addFieldToFilter('form_id',$formId);
           $this->addValuesToResult((int) $formId, $this->_collection);
            $this->_uncollection = clone $this->_collection;
            if($currentAgent)
            {
                $this->_collection->getSelect()->where('results_values_82.value = ?',$currentAgent);
            }else{
                $this->_collection->getSelect()->where('results_values_82.value in (?)',$this->getSellers());
            }
            if($month)
            {
                $this->_collection->addFieldToFilter('created_at',['like'=>'%'.$month.'%']);
            }
            return $this->_collection;
        }
    }


    public function getSellers()
    {
        if(!$this->_sellers)
        {
             if($this->_customerSession->getCustomerGroupId()==1)
            {
                $subAccount = $this->subAccountRepository->getByCustomerId($this->getCustomerId());
                if ($subAccount->getId()) {
                    $sellerId = $subAccount->getSellerId();
                }else{
                    $sellerId = $this->getCustomerId();
                }

            }else{
                $sellerId = $this->getCustomerId();
            }
            $subSellers = $this->subAccountRepository->getBySellerId($sellerId);
            $rvpAgentIds = $subSellers->getColumnValues('customer_id');
            $rvpAgentIds[] = $sellerId;
            $this->_sellers = $rvpAgentIds;
        }
        if($this->_customerSession->getCustomerGroupId()==1){
            return $rvpAgentIds = $this->getCustomerId();
        }else{
            return $this->_sellers;
        }
    }


    public function getCurrentAgent()
    {
        return $this->getRequest()->getParam('agent');
    }

     public function getCurrentMonth()
    {
        return $this->getRequest()->getParam('month');
    }

    public function getAgentList()
    {
        $sellers = $this->getSellers();

        $agents =[];
        foreach($sellers as $_data)
        {
             try {
                $customer = $this->customerRepository->getById($_data);
                $agents[$_data] = $customer->getFirstname() . ' . ' . $customer->getLastname();
            } catch (\Exception $e) {
                
            }
        }
        return $agents;
    }

    public function getMonths()
    {

        $this->_uncollection->addFieldToFilter('results_values_82.value',$this->getSellers());
        $this->_uncollection->getSelect()->reset(\Zend_Db_Select::COLUMNS)->columns(['created_at AS months_years'])->group(array(new \Zend_Db_Expr('year(created_at)-month(created_at)')));
        $data = [];
        
        foreach($this->_uncollection as $_data)
        {
             try {
                $data[] = $_data['months_years'];
            } catch (\Exception $e) {
                
            }
        }

        return $data;
    }


    /**
     * @param \MageMe\WebForms\Model\ResourceModel\Result\Grid\Collection $collection
     */
    public function addValuesToResult(int $formId, $collection)
    {
        $fields = $this->getFieldsIds($formId, $collection);
        if (count($fields) < 60) {
            foreach ($fields as $field) {
                $fieldId = $field['field_id'];
                $code = $field['code'];
                $resultValues = $collection->getResource()->getConnection()->select()
                    ->from($collection->getTable(ResultValueResource::DB_TABLE),
                        ['_'.ResultValueInterface::RESULT_ID => ResultValueInterface::RESULT_ID, ResultValueInterface::VALUE]
                    )
                    ->where(ResultValueInterface::FIELD_ID . '=?', $fieldId);
                    if($code == 'initials')
                    {
                        $collection->getSelect()->join(
                            ['results_values_' . $fieldId => $resultValues],
                            'main_table. ' . ResultInterface::ID . ' = results_values_' . $fieldId . '._' . ResultValueInterface::RESULT_ID,
                            ["$code" => "results_values_$fieldId." . ResultValueInterface::VALUE]
                        );

                    }else{
                        $collection->getSelect()->joinLeft(
                            ['results_values_' . $fieldId => $resultValues],
                            'main_table. ' . ResultInterface::ID . ' = results_values_' . $fieldId . '._' . ResultValueInterface::RESULT_ID,
                            ["$code" => "results_values_$fieldId." . ResultValueInterface::VALUE]
                        );
                    }
            }
        }
    }

    /**
     * Get fields ids
     *
     * @param int $webformId
     * @param \MageMe\WebForms\Model\ResourceModel\Result\Grid\Collection $collection
     * @return array
     */
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

    public function getResultIds(){
        $collections = $this->getSurveyCollection();
        foreach($collections as $collection){
            return $collection->getResultId();
        }
    }

    // get rvp_agent_id
    public function getCustomerFieldId(){
        $resultValueTable = $this->resourceConnection->getTableName('mm_webforms_field');

        //Initiate Connection
        $connection = $this->resourceConnection->getConnection();

        //SELECT `form_id`, `field_id` FROM `mm_webforms_field` WHERE `code` LIKE 'rvp_agent_id'
        $code = 'rvp_agent_id';
        $select = $connection->select()
            ->from(
                ['wff' => $resultValueTable],
                ['form_id','field_id']
            )
            ->where(
                "wff.code = ?", $code
            );

        return $connection->fetchAll($select);

    }

    public function getResultValue($result_id){
        //SELECT * FROM `mm_webforms_result_value` WHERE `result_id` = 150

        $resultValueTable = $this->resourceConnection->getTableName('mm_webforms_result_value');

        $connection = $this->resourceConnection->getConnection();

        $select = $connection->select()
            ->from(
                ['wff' => $resultValueTable],
                ['*']
            )
            ->where(
                "wff.result_id = ?", $result_id
            );

        return $connection->fetchAll($select);
    }

    function currencyFormat($num) {
        if (!$num) {
            return 'N/A';
        }
        $num = (int) $num;
        if($num>1000) {

              $x = round($num);
              $x_number_format = number_format($x);
              $x_array = explode(',', $x_number_format);
              $x_parts = array('K', 'M', 'B', 'T');
              $x_count_parts = count($x_array) - 1;
              $x_display = $x;
              $x_display = $x_array[0] . ((int) $x_array[1][0] !== 0 ? '.' . $x_array[1][0] : '');
              $x_display .= $x_parts[$x_count_parts - 1];

              return '$' . $x_display;

        }

        return '$' . $num;
      }

      public function getCustomerName($id)
      {
        if ($id) {
            try {
                if(!isset($this->name[$id])){
                    $customer = $this->customerRepository->getById($id);
                    $this->name[$id] = $customer->getFirstname() . ' . ' . $customer->getLastname();    
                }
                return $this->name[$id];
                
            } catch (\Exception $e) {
                return 'N/A';
            }
        }
      }
}
