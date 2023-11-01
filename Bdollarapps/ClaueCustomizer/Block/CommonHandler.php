<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Bdollarapps\ClaueCustomizer\Block;

/**
 * Common Handlerblock
 */
class CommonHandler extends \Magento\Framework\View\Element\Template
{    
    protected $userContext;

    protected $customerRepository;

    protected $_customerSession;

    public function __construct(
            \Magento\Framework\View\Element\Template\Context $context,
            \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
            \Magento\Customer\Model\Session $customerSession,
            \Magento\Checkout\Model\Cart $cart,
            \Magento\Framework\ObjectManagerInterface $objectManager,
            \Magento\Authorization\Model\UserContextInterface $userContext,
            array $data = []
        
        ) {
            parent::__construct($context, $data);
            $this->customerRepository = $customerRepository;
            $this->_customerSession = $customerSession;
            $this->_cart = $cart;
            $this->_objectManager = $objectManager;
            $this->userContext = $userContext;
        }

        public function getCartStatus()
        {
            $productInfo = $this->_cart->getQuote()->getItemsCollection();
            foreach ($productInfo as $item){
               if(!empty($item->getProductId()))
               {
                    return true;
               }
               else
               {
                    return false;
               }
            }
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

    public function getLevel3Access($customerID)
    {
        if($this->_customerSession->isLoggedIn()):
            $customer = $this->customerRepository->getById($customerID);
            if($level_3 = $customer->getCustomAttribute('level_3')) {
                return $level_3->getValue();
            }else{
                return;
            }
        endif;
            return;
    }

    public function getLevel4Access($customerID)
    {
        if($this->_customerSession->isLoggedIn()):
            $customer = $this->customerRepository->getById($customerID);
            if($level_4 = $customer->getCustomAttribute('level_4')) {
                return $level_4->getValue();
            }else{
                return;
            }
        endif;
            return;
    }

    public function getPrimericaStatus($customerID)
    {
        $customer = $this->customerRepository->getById($customerID);
        return $customer->getCustomAttribute('primerica_active_status')->getValue();
    }

    public function redirectLogout($primericaStatus, $customerID){
        //echo $customerID;
        //exit();
        if(!$primericaStatus && !empty($customerID)) {
            return $this->_customerSession->logout()
                  ->setBeforeAuthUrl($this->_redirect->getRefererUrl());
                  //->setLastCustomerId($customerID);
        }else{
            return;
        }
    }

}