<?php
namespace Bdollarapps\Subscription\Helper;
use Magento\Customer\Model\Session;
class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
   public function __construct(\Magento\Customer\Api\CustomerRepositoryInterface $customerRepository, Session $customerSession, \Magento\Customer\Api\AccountManagementInterface $accountManagement)
   {
        $this->customerRepository = $customerRepository;
        $this->_customerSession = $customerSession;
        $this->accountManagement = $accountManagement;
   }
   public function getCustomerBillsById()
    {
        $customer = $this->getCustomer($this->_customerSession->getId());
        if($customer->getCustomAttribute('billsby_customer_id'))
        {
            return $customer->getCustomAttribute('billsby_customer_id')->getValue();   
        }
        return '';
    }

    public function getCustomer($id)
    { 
        return $this->customerRepository->getById($id);
    }

    public function getCustomerInfo()
    {
        $customer = $this->getCustomer($this->_customerSession->getId());
        return $customer;
    }



    public function getSubscriptionDetail($customerId)
    {
         try {
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://public.billsby.com/api/v1/rest/core/bdollarsmart/customers/' . $customerId . '/subscriptions',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'ApiKey: bdollarsmart_a55f7aa07e3146409fbcc72cb0585fe6'
                ),
            ));
            $response = curl_exec($curl);
            curl_close($curl);

            return $response;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function getBillsInvoice($customerId){
        try {
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://public.billsby.com/api/v1/rest/core/bdollarsmart/customers/'.$customerId.'/invoices',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'ApiKey: bdollarsmart_a55f7aa07e3146409fbcc72cb0585fe6'
                ),
            ));
            $response = curl_exec($curl);
            curl_close($curl);
    
            return $response;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }


    public function getDefaultBillingAddress()
     {
         try {
            $customerId = $this->_customerSession->getId();
            $address = $this->accountManagement->getDefaultBillingAddress($customerId);
         } catch (\Exception $e) {
            return '';
         }
         return $address;
     }
}