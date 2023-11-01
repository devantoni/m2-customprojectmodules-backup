<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Bdollarapps\ReportSystem\Block\Report;

use Bdollarapps\MagemeForms\Helper\Data;
use Magento\Framework\App\ResourceConnection;

class Availablesssets extends \Magento\Framework\View\Element\Template
{
    protected $_surveyCollectionFactory;

    protected $userContext;

    protected $customerRepository;

    protected $_customerSession;

    private ResourceConnection $resourceConnection;

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
        \MageMe\WebForms\Model\ResourceModel\Result\CollectionFactory $_surveyCollectionFactory,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Authorization\Model\UserContextInterface $userContext,
        ResourceConnection $resourceConnection,
        Data $helper,
        array $data = []
    ) {
        $this->_surveyCollectionFactory = $_surveyCollectionFactory;
        $this->customerRepository = $customerRepository;
        $this->_customerSession = $customerSession;
        $this->_objectManager = $objectManager;
        $this->userContext = $userContext;
        $this->resourceConnection = $resourceConnection;
        $this->helper = $helper;
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
        $collection = $this->_surveyCollectionFactory->create();
        
        $formId = $this->helper->getSurveyFormId()?$this->helper->getSurveyFormId():2;

        $collection->addFieldToFilter('form_id',$formId);

        return $collection;
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
}

