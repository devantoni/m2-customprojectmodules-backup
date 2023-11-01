<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Bdollarapps\ReportSystem\Controller\Report;

use Magento\Customer\Model\ResourceModel\CustomerRepository;
use Magento\Framework\App\ResourceConnection;
use Psr\Log\LoggerInterface as Logger;

class Getpriority extends \Magento\Framework\App\Action\Action
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
        CustomerRepository $customerRepository,
        ResourceConnection $resource,
        \Magento\Framework\Controller\Result\JsonFactory $jsonFactory,
        Logger $logger

    ) {
        parent::__construct($context);
        $this->context           = $context;
        $this->customerRepository = $customerRepository;
        $this->resource = $resource;
        $this->jsonFactory = $jsonFactory;       
        $this->_logger = $logger;
    }
    
    
    public function execute()
    {
        // echo "test";
        // exit();
        $jsonFactory =  $this->jsonFactory->create();

        $uniqueSurveyId=$this->getRequest()->getParam('unique_survey_id');
 
        $connection = $this->resource->getConnection();
        $tableName = $connection->getTableName('bdassist_priority');

        $fetchData = $connection->select()
        ->from(
            ['bp' => 'bdassist_priority'],
            ['id', 'unique_survey_id', 'customer_id', 'priority_value', 'client', 'checkup_alert']
        )->where(
            'bp.unique_survey_id = :unique_survey_id'
        );
        
        $bind = ['unique_survey_id'=>$uniqueSurveyId];
        

        $query = $connection->fetchAll($fetchData, $bind);
        //var_dump($query); die;

        if (!empty($query)){

            // var_dump($query); die;
            // foreach($query as $key => $val){

            //         $unique_survey_id = $val['unique_survey_id'];
            //         $checkup_alert = $val['checkup_alert'];
            //         $customer_id =  $val['customer_id'];
            //         $priorityValue = $val['priority_value'];
            //         $_client = $val['client'];
            //         $id = $val['id'];
            //     }

            //$finalrespose = json_decode($query);

            return $jsonFactory->setData($query);
   
        }
        //echo "some";
    }
}