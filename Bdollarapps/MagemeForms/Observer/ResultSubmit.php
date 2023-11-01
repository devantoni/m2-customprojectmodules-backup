<?php

namespace Bdollarapps\MagemeForms\Observer;

use Bdollarapps\MagemeForms\Service\TokeniseSMS;
use MageMe\WebForms\Model\Repository\FieldRepository;
use Bdollarapps\MagemeForms\Model\TokeniseLink;
use Bdollarapps\MagemeForms\Model\TokeniseLinkFactory;
use Bdollarapps\MagemeForms\Helper\Data;
use Magento\Customer\Model\SessionFactory;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Model\Session as CustomerSession;

class ResultSubmit implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var FieldRepository
     */
    protected $fieldRepo;

    /**
     * @var TokeniseSMS
     */
    protected $tokeniseSMS;

    /**
     * @var TokeniseLinkFactory $tokeniseLinkFactory
     */
    protected $tokeniseLinkFactory;

    /**
     * @var Data
     */
    protected $helper;

    /**
     * @var SessionFactory
     */
    protected $sessionFactory;

    protected $_request;

    protected $customerRepository;

    protected $customerSession;

    protected $_messageManager;

    public function __construct(
        FieldRepository $fieldRepo,
        TokeniseSMS $tokeniseSMS,
        TokeniseLinkFactory $tokeniseLinkFactory,
        Data $helper,
        SessionFactory $sessionFactory,
        \Magento\Framework\App\RequestInterface $request,
        CustomerRepositoryInterface $customerRepository,
        CustomerSession $customerSession,
        \Magento\Framework\Message\ManagerInterface $messageManager
    )
    {
        $this->fieldRepo = $fieldRepo;
        $this->tokeniseSMS = $tokeniseSMS;
        $this->tokeniseLinkFactory = $tokeniseLinkFactory;
        $this->helper = $helper;
        $this->sessionFactory = $sessionFactory;
        $this->_request = $request;
        $this->_customerRepository = $customerRepository;
        $this->_customerSession = $customerSession;
        $this->_messageManager = $messageManager;  
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $result = $observer->getEvent()->getResult();

        $fields = $this->_request->getPost('field');

        $state_code = $fields['180'];
        
        $customerId  = $this->_customerSession->getCustomer()->getId();

        //$groupId  = $this->_customerSession->getCustomer()->getGroupId();

        $customerData = $this->_customerRepository->getById($customerId);

        $solutionNumber = $customerData->getCustomAttribute('solution_number')->getValue();

        $accessToken = $this->getPrimericaOauthToken();

        if(!empty($accessToken) && !empty($solutionNumber) && !empty($state_code)){
            
            $rvpDetails = $this->getRvpDetails($accessToken, $solutionNumber);

            $type = "Life";
            
            foreach ($rvpDetails as $key => $value) {
                
                if($value['type'] == "life"){

                    $type = $value['type'];
                    $lifeStates[] = $value['states'];

                }else{

                    $type = $value['type'];
                    $securitiesState[] = $value['states'];

                }

            }

            if(!empty($lifeStates) && in_array($state_code, $lifeStates[0])){
                $this->_messageManager->addSuccess(__('Checkup send successfully'));

            }elseif(!empty($securitiesState) && in_array($state_code, $securitiesState[0])){
                $this->_messageManager->addSuccess(__('Checkup send successfully'));

            }else{
                $this->_messageManager->addError(__('Recipient cannot be located in a state/territory where you are not '. $type .'-Licensed'));
                exit();
            }
            

        }

        if (!$this->helper->getIsEnabled()) {
            return null;
        }

        if ($result instanceof \MageMe\WebForms\Model\Result) {
            $result = $result->getData();
        }

        if ($result) {
            $list = $this->fieldRepo->getListByWebformId($result['form_id'], $result['store_id']);
            $isSmsExists = false;
            $toSmsNumber = null;
            $fieldId = null;
            $langFieldId = null;
            $checkupId = null;
            foreach ($list->getItems() as $field) {
                if ($field instanceof \MageMe\WebForms\Model\Field\Type\PhoneNumber) {
                    if ($field->getTwilioSms() == 1 && !$fieldId) {
                        $isSmsExists = true;
                        $fieldId = 'field_' . $field->getFieldId();
                    }
                }
                if ($field instanceof \MageMe\WebForms\Model\Field\Type\SelectRadio && !$langFieldId) {
                    if ($field->getCode() == 'lang') {
                        $langFieldId = 'field_' . $field->getFieldId();
                    }
                }
                if ($field instanceof \MageMe\WebForms\Model\Field\Type\SelectRadio && !$checkupId) {
                    if ($field->getCode() == 'checkup_type') {
                        $checkupId = 'field_' . $field->getFieldId();
                    }
                }

                // If both fields have data then break the loop
                if ($field && $langFieldId && $checkupId) {
                    break;
                }
            }

            if ($isSmsExists) {
                $toSmsNumber = $result[$fieldId];
                $lang = $result[$langFieldId];
                $checkup = $result[$checkupId];
                $bytes = random_bytes(10);
                $url = bin2hex($bytes);
                /** @var \Magento\Customer\Model\Session $session */
                $session = $this->sessionFactory->create();

                /** @var TokeniseLink $tokeniseLink */
                $tokeniseLink = $this->tokeniseLinkFactory->create();
                $tokeniseLink->addData(
                    [
                        'to_mobile' => $toSmsNumber,
                        'tokenise_url' => $url,
                        'customer_id' => $session->getCustomerId(),
                        'lang' => $lang,
                        'type'=> $checkup
                    ]
                    );
                $tokeniseLink->save();
                $this->tokeniseSMS->sendTokeniseSMS($toSmsNumber, $lang, $url);
            }
        }
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
  
        return $result['access_token'];
  
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
  
        return $result['licenses'];
      }
}
