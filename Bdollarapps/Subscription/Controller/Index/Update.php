<?php
declare(strict_types=1);

namespace Bdollarapps\Subscription\Controller\Index;

use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\App\Action\HttpPostActionInterface as HttpPostActionInterface;
class Update extends \Magento\Framework\App\Action\Action implements HttpPostActionInterface
{

    public function __construct(\Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,\Magento\Customer\Model\ResourceModel\Customer\Collection $collection,\Magento\Customer\Api\CustomerRepositoryInterface $customerRepositoryInterface,\Magento\Framework\App\Action\Context $context)
    {
        $this->resultJsonFactory = $resultJsonFactory;
        $this->customerCollection = $collection;
        $this->customerRepository = $customerRepositoryInterface;
        return parent::__construct($context);
    }

    /**
     * Execute view action
     *
     * @return ResultInterface
     */
    public function execute()
    {
        $payload = file_get_contents('php://input');
        $writer = new \Zend_Log_Writer_Stream(BP . '/var/log/custom.log');
		$logger = new \Zend_Log();
		$logger->addWriter($writer);
		$logger->info($payload);
		$data = json_decode($payload,true);
		if(isset($data['Type']) && ($data['Type'] == 'SubscriptionCreated' || $data['Type'] == 'SubscriptionUpdated')){
			$this->customerCollection->addAttributeToFilter('billsby_customer_id',$data['CustomerUniqueId']);
			if(!empty($customerData = $this->customerCollection->getData()))
			{
				$dataCustomer = $customerData[0];
				$customer =$this->customerRepository->getById($dataCustomer['entity_id']);
				if($data['SubscriptionStatus']=='Active')
				{
					$customer->setCustomAttribute('billsby_subscription_status', true);	
				}else{
					$customer->setCustomAttribute('billsby_subscription_status', false);	
				}
				$this->customerRepository->save($customer);
			}
			
		}
		$resultJson = $this->resultJsonFactory->create();
		$result = true;
		return $resultJson->setData(['success' => $result]);
	
    }
}

