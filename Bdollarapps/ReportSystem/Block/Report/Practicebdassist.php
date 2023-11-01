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
class Practicebdassist extends \Magento\Framework\View\Element\Template
{


    const ALLOWED_FIELD_CODES = [
            'rvp_agent_id',
            'initials',
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

    /**
     * @return string
     */
    public function checkupResultsData()
    {
        //Your block code
        return __('Hello Developer! This how to get the storename: %1 and this is the way to build a url: %2', $this->_storeManager->getStore()->getName(), $this->getUrl('contacts'));
    }

    public function getAgentList()
    {
        $sellers = $this->getSellers();

        $agents =[];
        foreach($sellers as $_data)
        {
             try {
                $customer = $this->customerRepository->getById($_data);
                $agents[$_data] = $customer->getFirstname() . ' ' . $customer->getLastname();
            } catch (\Exception $e) {
                
            }
        }
        return $agents;
    }


     public function getSellers()
    {
        if(!$this->_sellers)
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
            $this->_sellers = $rvpAgentIds;
        }
       
        if($this->_customerSession->getCustomerGroupId()==1){
            return $rvpAgentIds = $this->getCustomerId();
        }else{
            return $this->_sellers;
        }
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

    public function getEmail()
    {
        return $this->_customerSession->getCustomer()->getEmail();
    }

    public function getLifeLicense($customerID)
    {
        if($this->_customerSession->isLoggedIn()):
            $customer = $this->customerRepository->getById($customerID);
            if($app_access = $customer->getCustomAttribute('life_licensed')) {
                return $app_access->getValue();
            }else{
                return;
            }
        endif;
            return;
    }

    public function getSecurityLicense($customerID)
    {
        if($this->_customerSession->isLoggedIn()):
            $customer = $this->customerRepository->getById($customerID);
            if($app_access = $customer->getCustomAttribute('securities_licensed')) {
                return $app_access->getValue();
            }else{
                return;
            }
        endif;
            return;
    }

    public function getFullAccess($customerID)
    {
        if($this->_customerSession->isLoggedIn()):
            $customer = $this->customerRepository->getById($customerID);
            if($app_access = $customer->getCustomAttribute('app_access')) {
                return $app_access->getValue();
            }else{
                return;
            }
        endif;
            return;
    }

    public function getLevel1Access($customerID)
    {
        if($this->_customerSession->isLoggedIn()):
            $customer = $this->customerRepository->getById($customerID);
            if($level_1 = $customer->getCustomAttribute('level_1')) {
                return $level_1->getValue();
            }else{
                return;
            }
        endif;
            return;
    }

    public function getLevel2Access($customerID)
    {
        if($this->_customerSession->isLoggedIn()):
            $customer = $this->customerRepository->getById($customerID);
            if($level_2 = $customer->getCustomAttribute('level_2')) {
                return $level_2->getValue();
            }else{
                return;
            }
        endif;
            return;
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
            //echo $this->_collection->getSelect();exit;
            $this->_collection->getSelect()->order('created_at DESC');

            return $this->_collection;
        }
    }

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

     public function getCustomerName($id)
      {
        if ($id) {
            try {
                if(!isset($this->name[$id])){
                    $customer = $this->customerRepository->getById($id);
                    $this->name[$id] = $customer->getFirstname() . ' ' . $customer->getLastname();    
                }
                return $this->name[$id];
                
            } catch (\Exception $e) {
                return 'N/A';
            }
        }
      }


    public function getCurrentAgent()
    {
        return $this->getRequest()->getParam('agent');
    }
}

