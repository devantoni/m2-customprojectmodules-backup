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

class Savepriority extends \Magento\Framework\App\Action\Action
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
        Logger $logger

    ) {
        parent::__construct($context);
        $this->context           = $context;
        $this->customerRepository = $customerRepository;
        $this->resource = $resource;
        $this->_logger = $logger;
    }
    
    
    public function execute()
    {
        // echo "test";
        // exit();

        $uniqueSurveyId=$this->getRequest()->getPost('unique_survey_id');
        $customerId=$this->getRequest()->getPost('customer_id');
        $priorityValue=$this->getRequest()->getPost('priority_value');
        $client=$this->getRequest()->getPost('client');
        $checkupAlert=$this->getRequest()->getPost('checkup_alert');
        $status=$this->getRequest()->getPost('status');

        $dataset = [
            'unique_survey_id' => $uniqueSurveyId,
            'customer_id' => $customerId,
            'priority_value' => $priorityValue,
            'client' => $client,
            'checkup_alert' => $checkupAlert,
        ];

        $connection = $this->resource->getConnection();
        $tableName = $connection->getTableName('bdassist_priority');

        $fetchData = $connection->select()
        ->from(
            ['bp' => 'bdassist_priority'],
            ['id', 'unique_survey_id', 'customer_id', 'priority_value', 'client', 'checkup_alert']
        )->where(
            'bp.client = :client'
        )->where(
            'bp.unique_survey_id = :unique_survey_id'
        )->where(
            'bp.checkup_alert = :checkup_alert'
        )->where(
            'bp.customer_id = :customer_id'
        );
        
        $bind = ['client'=>$client, 'unique_survey_id'=>$uniqueSurveyId, 'checkup_alert'=>$checkupAlert, 'customer_id'=>$customerId ];
        

        $query = $connection->fetchAll($fetchData, $bind);

        if (!empty($query)){

            foreach($query as $key => $val){

                    $unique_survey_id = $val['unique_survey_id'];
                    $checkup_alert = $val['checkup_alert'];
                    $customer_id =  $val['customer_id'];
                    $_client = $val['client'];
                    $id = $val['id'];
                }
                if ($unique_survey_id == $uniqueSurveyId && $checkup_alert == $checkupAlert && $customer_id == $customerId && $_client == $client){
                    try {
                        $connection->update($tableName,
                            $dataset,
                            ['id = ?' => (int)$id]
                        );
                    } catch (\Exception $e) {
                        $this->_logger->critical($e->getMessage());
                    }
                }else{
                    try {
                        $connection->insert($tableName, $dataset);
                    } catch (\Exception $e) {
                        $this->_logger->critical($e->getMessage());
                    }
                }
                
            }else{
                // for blank record
                try {
                    $connection->insert($tableName, $dataset);
                } catch (\Exception $e) {
                    $this->_logger->critical($e->getMessage());
                }
            }
    }
}