<?php

/**
 *
 */
namespace Bdollarapps\ClaueCustomizer\Plugin;

/**
 *
 */
class LoginPostPlugin
{

    /**
     * Change redirect after login to home instead of dashboard.
     *
     * @param \Magento\Customer\Controller\Account\LoginPost $subject
     * @param \Magento\Framework\Controller\Result\Redirect $result
     */
    public function afterExecute(
        \Magento\Customer\Controller\Account\LoginPost $subject,
        $result)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $customerSession = $objectManager->create('Magento\Customer\Model\Session');
        if ($customerSession->isLoggedIn()){
            $groupId = $customerSession->getCustomerGroupId();
            if ($groupId == 1){
                $result->setPath('agent-dashboard');
            }elseif($groupId == 2){
                $result->setPath('rvp-dashboard');
            }else{
                $result->setPath('/');
            }
        }
        return $result;

    }
    
}
