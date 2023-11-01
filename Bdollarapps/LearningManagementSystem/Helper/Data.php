<?php
namespace Bdollarapps\LearningManagementSystem\Helper;
use \Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Amasty\RecurringPayments\Model\SubscriptionManagement;
use Webkul\SellerSubAccount\Model\SubAccountRepository;
class Data extends AbstractHelper
{
       protected $_productCollectionFactory;

       public function __construct(
              Context $context,        
              SubscriptionManagement $subscriptionManagement,
              \Webkul\LearningManagementSystem\Model\CourseStatusFactory $courseStatusFactory,
              \Webkul\LearningManagementSystem\Model\CourseContentFactory $courseContentFactory,
              \Magento\Customer\Model\Session $customerSession,
              \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
              SubAccountRepository $subAccountRepository
       )
       {    
              $this->subscriptionManagement = $subscriptionManagement; 
              $this->courseStatusFactory = $courseStatusFactory;
              $this->courseContentFactory = $courseContentFactory;
              $this->customerSession = $customerSession;  
              $this->customerRepository = $customerRepository;
              $this->subAccountRepository = $subAccountRepository;
              parent::__construct($context);
       }

       public function getActiveSubscription()
       {
               if($this->customerSession->getCustomerGroupId()==1)
               {
                 $subAccount = $this->subAccountRepository->getByCustomerId($this->customerSession->getCustomerId());
                if ($subAccount->getId()) {
                    $sellerId = $subAccount->getSellerId();
                }else{
                    $sellerId = $this->customerSession->getCustomerId();
                }
                if($sellerId)
                {
                    if($this->getSubscriptionType($sellerId))
                     {
                            if($this->getSubscriptionStatus())
                            {
                                   return true;
                            }
                            return false;
                     }else{
                            $subscritption = $this->subscriptionManagement->getSubscriptions((int)$sellerId);
                            foreach($subscritption as $key=>$value)
                            {
                                   if($value->getStatus()=='Active')
                                   {
                                          return true;
                                   }
                            }
                            return false;
                     }
                }
              }else{
                     if($this->getSubscriptionType())
                     {
                            if($this->getSubscriptionStatus())
                            {
                                   return true;
                            }
                            return false;
                     }else{
                            $customerId = $this->customerSession->getCustomerId();
                            $subscritption = $this->subscriptionManagement->getSubscriptions((int)$customerId);
                            foreach($subscritption as $key=>$value)
                            {
                                   if($value->getStatus()=='Active')
                                   {
                                          return true;
                                   }
                            }
                            return false;
                     }
              }
              

       }

       public function getQuickCourseFinish()
       {
              if($this->getSubscriptionType())
              {
                     return true;
              }else{
                     $customerId = $this->customerSession->getCustomerId();
                     $courseStatusCollection = $this->courseStatusFactory->create()
                                               ->getCollection()
                                               ->addFieldToFilter('course_id', 5)
                                               ->addFieldToFilter('content_id', 0)
                                               ->addFieldToFilter('customer_id', $customerId);

                     if ($courseStatusCollection->getSize()>0) {
                            return true;
                     }elseif(!$courseStatusCollection->getSize())
                     {
                            $courseStatusCollection = $this->courseStatusFactory->create()
                                               ->getCollection()
                                               ->addFieldToFilter('course_id', 5)
                                               ->addFieldToFilter('customer_id', $customerId);
                            $count = $courseStatusCollection->getSize();

                            $courseContentCollection = $this->courseContentFactory->create()
                                               ->getCollection()
                                               ->addFieldToFilter('course_id', 5);
                            $coursecount = $courseContentCollection->getSize();
                            if($count == $coursecount)
                            {
                                   return true;
                            }

                     }     
              } 
              return false;

       }

       public function getSubscriptionType($customerId='')
       {      
              if($customerId==''){
                     $customerId = $this->customerSession->getCustomerId();
              }
              $customer = $this->getCustomer($customerId);
               if($customer->getCustomAttribute('billsby_customer_id'))
               {
                   return $customer->getCustomAttribute('billsby_customer_id')->getValue();   
               }
               return false;
       }

       public function getSubscriptionStatus()
       {      
              $customerId = $this->customerSession->getCustomerId();
              $customer = $this->getCustomer($customerId);
              if($customer->getCustomAttribute('billsby_subscription_status'))
              {
                   return $customer->getCustomAttribute('billsby_subscription_status')->getValue();   
              }
              return false;
       }


       public function getCustomer($id)
       { 
              return $this->customerRepository->getById($id);
       }

}