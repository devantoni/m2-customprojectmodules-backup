<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Bdollarapps\ReportSystem\Block\Report;

use Bdollarapps\MagemeForms\Helper\Data;
use Webkul\SellerSubAccount\Model\SubAccountRepository;
use MageMe\WebForms\Model\ResourceModel\Field as FieldResource;
use MageMe\WebForms\Api\Data\ResultInterface;
use MageMe\WebForms\Api\Data\FieldInterface;
use MageMe\WebForms\Model\ResourceModel\ResultValue as ResultValueResource;
use MageMe\WebForms\Api\Data\ResultValueInterface;

class Usagereport extends \Magento\Framework\View\Element\Template
{
    const ALLOWED_FIELD_CODES = [
        'rvp_agent_id'
    ];

    protected $_surveyCollectionFactory;

    protected $userContext;

    protected $customerRepository;

    protected $_customerSession;

    /**
     * @var Data
     */
    private $helper;

    /**
     * @var SubAccountRepository
     */
    private $subAccountRepository;

    /**
     * Constructor
     *
     * @param \Magento\Framework\View\Element\Template\Context  $context
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \MageMe\WebForms\Model\ResourceModel\Result\CollectionFactory $_surveyCollectionFactory,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Authorization\Model\UserContextInterface $userContext,
        Data $helper,
        SubAccountRepository $subAccountRepository,
        array $data = []
    ) {
        $this->_surveyCollectionFactory = $_surveyCollectionFactory;
        $this->customerRepository = $customerRepository;
        $this->_customerSession = $customerSession;
        $this->_objectManager = $objectManager;
        $this->userContext = $userContext;
        $this->helper = $helper;
        $this->subAccountRepository = $subAccountRepository;
        parent::__construct($context, $data);
    }

    /**
     * @return string
     */
    public function usageReportData()
    {
        //Your block code
        return __('Hello Developer! This how to get the storename: %1 and this is the way to build a url: %2', $this->_storeManager->getStore()->getName(), $this->getUrl('contacts'));
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

    public function getSurveyCollection()
    {
        $rvpAgentIds = $this->getSellers();
        $formId = $this->helper->getSurveyFormId()?$this->helper->getSurveyFormId():2;
        $this->getRequest()->setParams(['form_id', (int) $formId]);

        $collection = $this->_surveyCollectionFactory->create();
        $this->addValuesToResult((int) $formId, $collection, $rvpAgentIds);
        $collection->addFieldToFilter('form_id',$formId);
        $this->_collection = $collection;
        return $collection;
    }


    public function getSellers()
    {
        if($this->_customerSession->getCustomerGroupId()==1)
        {
            $subAccount = $this->subAccountRepository->getByCustomerId($this->getCustomerId());
            if ($subAccount->getId()) {
                $subAccount = $this->subAccountRepository->getActiveByCustomerId($this->getCustomerId());
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
        if($this->_customerSession->getCustomerGroupId()==1):
            return $rvpAgentIds = $this->getCustomerId();
        else:
                return $rvpAgentIds;
        endif;
    }

    public function getRandomColorClass($key = 0) {

        $colors = ['bg-success','bg-danger','bg-primary','bg-secondary','bg-warning','bg-info','bg-dark'];
        $color = $colors[$key%7];

        return $color;
    }

    public function getNameInitials($name) {

        preg_match_all('#(?<=\s|\b)\pL#u', $name, $res);
        $initials = implode('', $res[0]);
        $initials = strtoupper(substr($initials, 0, 2));

        return $initials;
    }


    public function getMonths()
    {
        $collection  = clone $this->_collection;
        $collection->getSelect()->reset(\Zend_Db_Select::COLUMNS)->columns(['created_at AS months_years'])->group(array(new \Zend_Db_Expr('year(created_at)-month(created_at)')));
        
        $data = [];
        
        foreach($collection as $_data)
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
    public function addValuesToResult(int $formId, $collection, $ids)
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
                // rvp and agent condition
                $rvpAgentCondition = '';
                if ($code === 'rvp_agent_id') {
                if($this->_customerSession->getCustomerGroupId()==1){
                    $rvpAgentCondition = " AND results_values_$fieldId. " . ResultValueInterface::VALUE . ' IN (' . $ids . ')';
                }else{
                    $rvpAgentCondition = " AND results_values_$fieldId. " . ResultValueInterface::VALUE . ' IN (' . implode(',', $ids) . ')';
                }
                }
                $collection->getSelect()->join(
                    ['results_values_' . $fieldId => $resultValues],
                    'main_table. ' . ResultInterface::ID . ' = results_values_' . $fieldId . '._' . ResultValueInterface::RESULT_ID . $rvpAgentCondition,
                    ["$code" => "results_values_$fieldId." . ResultValueInterface::VALUE,
                    'totals' => 'count(*)'
                    ]
                );

                if ($code === 'rvp_agent_id') {
                    $collection->getSelect()->group("results_values_$fieldId." . ResultValueInterface::VALUE);
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

    public function getCustomerName($id)
    {
      if ($id) {
          try {
              $customer = $this->customerRepository->getById($id);

              return $customer->getFirstname() . ' ' . $customer->getLastname();
          } catch (\Exception $e) {
              return 'N/A';
          }
      }
    }

    public function getInitials($name)
    {
        $words = explode(' ', $name);
        if (count($words) >= 2) {
            return mb_strtoupper(
                    mb_substr($words[0], 0, 1, 'UTF-8') .
                    mb_substr(end($words), 0, 1, 'UTF-8'),
                'UTF-8');
        }
    }
}
