<?php

namespace Bdollarapps\LearningManagementSystem\Controller\Course;
use Magento\Framework\App\Action\Context;
use Webkul\LearningManagementSystem\Helper\Data;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\UrlInterface;
use Magento\Customer\Controller\AbstractAccount;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\App\Response\RedirectInterface;
use Webkul\LearningManagementSystem\Model\CourseContentFactory;
use Magento\Store\Model\StoreManagerInterface;
use Bdollarapps\LearningManagementSystem\Helper\Data as LearningData;
class View extends \Webkul\LearningManagementSystem\Controller\Course\View
{
    public function __construct(
        Data $helper,
        UrlInterface $url,
        Context $context,
        RedirectInterface $redirector,
        PageFactory $resultPageFactory,
        CourseContentFactory $courseContentFactory,
        StoreManagerInterface $storeManagerInterface,
        LearningData $learningHelper,
        \Magento\Customer\Model\Session\Proxy $sessionProxy,
        \Webkul\SellerSubAccount\Helper\Data $helperSubAccount
    ) {
        $this->helper = $helper;
        $this->url = $url;
        $this->context = $context;
        $this->redirector = $redirector;
        $this->resultPageFactory = $resultPageFactory;
        $this->courseContentFactory = $courseContentFactory;
        $this->storeManagerInterface = $storeManagerInterface;
        $this->lHelper = $learningHelper;
        $this->sessionProxy= $sessionProxy;
        $this->subSeller = $helperSubAccount;
        parent::__construct($helper,$url,$context,$redirector,$resultPageFactory,$courseContentFactory,$storeManagerInterface);
    }

	public function execute()
    {
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $id = $this->getRequest()->getParam('id');
        $cid = $this->getRequest()->getParam('cid');
        if (!$cid) {
            $contentCollection = $this->courseContentFactory->create()
            ->getCollection()
            ->addFieldToFilter('store_id', $this->storeManagerInterface->getStore()->getId())
            ->addFieldToFilter('course_id', $id);

            if ($contentCollection->getSize() == 0) {
                $contentCollection = $this->courseContentFactory->create()
                ->getCollection()
                ->addFieldToFilter('store_id', 0)
                ->addFieldToFilter('course_id', $id);
            }

            $cid = $contentCollection->getFirstItem()->getId();
            $this->getRequest()->setParam('cid', $cid);
        }
        
        if($this->sessionProxy->getCustomer()->getGroupId()!=1)
        {
            $isValidCourse = $this->helper->isValidCourse();

            if (!$this->lHelper->getActiveSubscription()) {
                $this->messageManager->addWarning(__("your subscription is not active"));
                $resultRedirect->setUrl($this->redirector->getRefererUrl());
                return $resultRedirect;
            }
        }
        $courseData = $this->helper->courseContentData();
        $error = true;
        if (!empty($courseData)) {
            foreach ($courseData as $sectionData) {
                foreach ($sectionData['content'] as $data) {
                    if ($data['content_id'] == $cid) {
                        $error = false;
                        break;
                    }
                }
            }
        }
        
        if ($error) {
            $norouteUrl = $this->url->getUrl('noroute');
            $resultRedirect->setUrl($norouteUrl);
            return $resultRedirect;
        }
        $resultPage = $this->resultPageFactory->create();
        return $resultPage;
    }
}