<?php

namespace Bdollarapps\ClaueCustomizer\Observer;

use Magento\Framework\Event\ObserverInterface;

use Magento\Customer\Api\CustomerRepositoryInterface;

use Magento\Customer\Model\Session as CustomerSession;

use Magento\Framework\App\ResourceConnection;

class RvpValidate implements ObserverInterface
{
  
  protected $customerRepository;

  protected $customerSession;

  protected $_request;

  protected $_objectManager;
  
  protected $_messageManager;

  private ResourceConnection $resourceConnection;

  /**
   * @var \Magento\Framework\App\ResponseFactory
   */
  private $responseFactory;

  /**
   * @var \Magento\Framework\UrlInterface
   */
  private $url;

 public function __construct(    
    CustomerRepositoryInterface $customerRepository,
    \Magento\Framework\App\Action\Context $context,
    CustomerSession $customerSession,
    \Magento\Customer\Model\CustomerFactory $customerFactory,
    \Magento\Framework\App\ResponseFactory $responseFactory,
    \Magento\Framework\UrlInterface $url,
    \Magento\Framework\App\RequestInterface $request,
    ResourceConnection $resourceConnection,
    \Magento\Framework\ObjectManagerInterface $objectmanager,
    \Magento\Framework\Message\ManagerInterface $messageManager,
  ){    
    $this->_customerRepository = $customerRepository;
    $this->_redirect = $context->getRedirect();
    $this->_customerSession = $customerSession;
    $this->_customerFactory = $customerFactory;
    $this->_responseFactory = $responseFactory;
    $this->_url = $url;
    $this->_request = $request;
    $this->_resourceConnection = $resourceConnection;
    $this->_objectManager = $objectmanager; 
    $this->_messageManager = $messageManager;   
  }

  public function execute(\Magento\Framework\Event\Observer $observer)
    {    
        $customer = $observer->getEvent()->getCustomer(); 

        $customerId = $customer->getId(); // current login user id (RVP or Agent)

        $customerGroupId = $customer->getGroupId(); 
        
        $customerData = $this->_customerFactory->create()->load($customerId)->getDataModel();


        // Oauth token generation from Primerica API - DEV ENV ::

        $accessToken = $this->getPrimericaOauthToken();
         
        // get rvp details from primerica api if customer group is 2

        //if($customerGroupId == 2){

          // $fields = $this->_request->getPost('field'); // for new rvp subscribtion

          // if(!empty($fields)){
          //   $solutionNumber = $fields['15'];
          // }else{
            $rvp = $this->_customerRepository->getById($customerId);

            $solutionNumber = $rvp->getCustomAttribute('solution_number')->getValue();
          // }
  
          if(!empty($accessToken) && !empty($solutionNumber)){
            try {
  
                $rvpDetails = $this->getRvpDetails($accessToken, $solutionNumber);
  
                $rvpStatus = $rvpDetails['activeStatus'];
  
                $rvpId = $rvpDetails['agentId'];
  
  
                // check if solution number is matching with primerica API and has activeStatus "active", then set the primerica_active_status to 1, By default we set this to 0.
                if($solutionNumber == $rvpId && $rvpStatus === 'active'){
                  
                  $customerData->setCustomAttribute('primerica_active_status', 1);
            
                  $this->_customerRepository->save($customerData);
                  
                  $this->_messageManager->addSuccess(__('Primerica Status Active'));

                }else{
                  $customerData->setCustomAttribute('primerica_active_status', 0);
          
                  $this->_customerRepository->save($customerData);
                  
                  $this->_messageManager->addError(__('Primerica status is not active. Please contact support for further assistance.'));

                  $this->_customerSession->logout()
                      ->setBeforeAuthUrl($this->_redirect->getRefererUrl())
                      ->setLastCustomerId($customerId);
                  
                }
  
            }catch (\Exception $e) {
  
              $customerData->setCustomAttribute('primerica_active_status', 0);
          
              $this->_customerRepository->save($customerData);
  
              $this->_objectManager->get('Psr\Log\LoggerInterface')->critical($e->getMessage());
              
              $this->_messageManager->addError(__('We were unable to process your request. Please try again! or contact administrator'));
              
              $this->_customerSession->logout()
                      ->setBeforeAuthUrl($this->_redirect->getRefererUrl())
                      ->setLastCustomerId($customerId);

              //$redirectionUrl = $this->_url->getUrl('customer/account/logout');
              
              //$this->_responseFactory->create()->setRedirect($redirectionUrl)->sendResponse();
              
            }
          }else{
              $customerData->setCustomAttribute('primerica_active_status', 0);
          
              $this->_customerRepository->save($customerData);
  
              $this->_messageManager->addError(__('Please review your solution number (or) authentication failed'));

              $this->_customerSession->logout()
                      ->setBeforeAuthUrl($this->_redirect->getRefererUrl())
                      ->setLastCustomerId($customerId);

              // $redirectionUrl = $this->_url->getUrl('customer/account/logout');
              // $this->_responseFactory->create()->setRedirect($redirectionUrl)->sendResponse();
          }
        // }else{
        //   // if customer group is 1
        //   $parentId = $this->getRvpIdOfAnAgent();

        //   $rvpSolutionNumber = $this->getRvpSolutionNumber($parentId);
          
        //   if (!empty($rvpSolutionNumber)) {
            
        //     $agentId  = $this->_customerSession->getCustomer()->getId();

        //     $customer = $this->_customerRepository->getById($agentId);

        //     $agentSolutionNumber = $customer->getCustomAttribute('solution_number')->getValue(); // 
  
        //     $agentDetails = $this->getAgentDetails($accessToken, $rvpSolutionNumber);
            
        //     // from the upline records loop around to match the agent id with agent solution number
        //     //echo "<pre>"; //var_dump($agentDetails);

        //     $agentStatus = array_search($agentSolutionNumber, array_column($agentDetails, 'agentId'));

        //     if(!$agentStatus){
        //       $customerData->setCustomAttribute('primerica_active_status', 0);
          
        //       $this->_customerRepository->save($customerData);
  
        //       $this->_customerSession->logout()
        //               ->setBeforeAuthUrl($this->_redirect->getRefererUrl())
        //               ->setLastCustomerId($agentId);

        //       $this->_messageManager->addError(__('Primerica status is not active. Please contact support for further assistance.'));

        //     }else{
        //       $customerData->setCustomAttribute('primerica_active_status', 1);
          
        //       $this->_customerRepository->save($customerData);
  
        //       $this->_messageManager->addSuccess(__('Agent Validation is successfull'));
        //     }
        //   }


        // }

        return $this;
    }

    public function getRvpIdOfAnAgent(){

      $agentId  = $this->_customerSession->getCustomer()->getId();

      $tableName = $this->_resourceConnection->getTableName('marketplace_sub_accounts');

      $connection = $this->_resourceConnection->getConnection();

      $select = $connection->select()
            ->from(
                ['c' => $tableName],
                ['seller_id']
            )
            ->where(
                "c.customer_id = :customer_id"
            );
      
      $bind = ['customer_id'=>$agentId];
      
      $query = $connection->fetchAll($select, $bind);
      
      if(!empty($query)){
        $rvpId = $query[0]['seller_id'];
      }else{
        $rvpId = 0;
      }

      return $rvpId;

    }

    public function getRvpSolutionNumber($rvpId){
            
      $customer = $this->_customerRepository->getById($rvpId);

      $rvpSolutionNumber = $customer->getCustomAttribute('solution_number')->getValue();
      
      return $rvpSolutionNumber;
    }

    public function getPrimericaOauthToken(){

      $curl = curl_init();

      $header = array(
        'Content-Type: application/x-www-form-urlencoded',
        'Authorization: Basic '.base64_encode("32b1f7b1-6a8f-492b-a0b8-034778d1f0a9:3097a872-8ffa-4a5c-b016-dfec7b07938c"),
        'Cookie: SMSESSION='
      );
      curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://gtwd.primericaonline.com/auth/oauth/v2/token?grant_type=client_credentials&scope=agent-core%3Aread',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_HTTPHEADER => $header,
      ));

      $response = curl_exec($curl);
  
      curl_close($curl);
  
      $result = json_decode($response, true); 

      if(!empty($result)){
        return $result['access_token'];
      }else{
        return null;
      }

    }


    public function getRvpDetails($accessToken, $solutionNumber){

      $curl = curl_init();

      curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://gtwd.primericaonline.com/dev/proc/agent/core/v1/agents/'.$solutionNumber.'?include=licenses.life%2Clicenses.securities%2Cuplines',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
          'Authorization: Bearer '.$accessToken,
          'Cookie: SMSESSION='
        ),
      ));
      
      $response = curl_exec($curl);
  
      curl_close($curl);

      $result = json_decode($response, true); 

      if(!empty($result)){
        return $result['agent'];
      }else{
        return null;
      }
    }

    public function getAgentDetails($accessToken, $rvpSolutionNumber){

      $curl = curl_init();

      curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://gtwd.primericaonline.com/dev/proc/agent/core/v1/agents/'.$rvpSolutionNumber.'?include=licenses.life%2Clicenses.securities%2Cuplines',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
          'Authorization: Bearer '.$accessToken,
          'Cookie: SMSESSION='
        ),
      ));
      
      $response = curl_exec($curl);
  
      curl_close($curl);

      $result = json_decode($response, true); 

      return $result['uplines'];
    }
}
