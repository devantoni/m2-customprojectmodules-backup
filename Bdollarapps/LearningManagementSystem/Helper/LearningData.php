<?php

namespace Bdollarapps\LearningManagementSystem\Helper;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\RequestInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory;
use Magento\Customer\Model\SessionFactory;
use Webkul\LearningManagementSystem\Model\CourseContentFactory;
use Webkul\LearningManagementSystem\Model\Config\Source\CourseSection;
use Webkul\LearningManagementSystem\Model\Config\Source\CourseLanguage;
use Webkul\LearningManagementSystem\Model\Config\Source\CourseLevelOptions;
use Webkul\LearningManagementSystem\Model\QARecordFactory;
use Webkul\LearningManagementSystem\Model\QAReplyFactory;
use Webkul\LearningManagementSystem\Model\CourseStatusFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Webkul\LearningManagementSystem\Model\CourseSectionFactory;
use Webkul\LearningManagementSystem\Model\ResourceModel\CourseSection\CollectionFactory as CollectionSectionFactory;
use \Magento\Eav\Model\Config;
use Webkul\LearningManagementSystem\Model\Driver\Driver;
class LearningData extends \Webkul\LearningManagementSystem\Helper\Data
{

	public function __construct(
        Context $context,
        RequestInterface $request,
        SessionFactory $customerSessionFactory,
        CollectionFactory $orderCollectionFactory,
        ProductRepositoryInterface $productRepository,
        \Magento\Sales\Api\OrderRepositoryInterface $orderRepository,
        \Magento\Framework\UrlInterface $urlInterface,
        CourseContentFactory $courseContentFactory,
        StoreManagerInterface $storeManagerInterface,
        CourseLanguage $courseLanguage,
        CourseLevelOptions $courseLevelOptions,
        QARecordFactory $qaRecordFactory,
        QAReplyFactory $qaReplyFactory,
        CourseStatusFactory $courseStatusFactory,
        CustomerRepositoryInterface $customerRepositoryInterface,
        CourseSection $courseSection,
        ScopeConfigInterface $scopeConfig,
        \Webkul\LearningManagementSystem\Logger\Logger $logger,
        CourseSectionFactory $courseSectionFactory,
        CollectionSectionFactory $collectionSectionFactory,
        Config $eavConfig,
        Driver $driver,
        \Magento\Customer\Api\GroupRepositoryInterface $groupInterface
    ) {
    	$this->groupInterface = $groupInterface;
        parent::__construct($context,$request,$customerSessionFactory,$orderCollectionFactory,$productRepository,$orderRepository,$urlInterface,$courseContentFactory,$storeManagerInterface,$courseLanguage,$courseLevelOptions,$qaRecordFactory,$qaReplyFactory,$courseStatusFactory,$customerRepositoryInterface,$courseSection,$scopeConfig,$logger,$courseSectionFactory,$collectionSectionFactory,$eavConfig,$driver);
    }


	public function getCurrentGroupId()
    {
        return $this->customerSessionFactory->create()->getData('customer_group_id');
    }

    public function getCurrentCustomerCourseIds()
    {
        $customerId = $this->getCurrentCustomerId();
        $orders = $this->getCustomerOrderCollection($customerId);

        $myCourses = [];
        foreach ($orders->getData() as $order) {
            if ($order['status'] == 'complete') {
                $data = $this->orderRepository->get($order['entity_id']);
                foreach ($data->getAllItems() as $item) {
                    if ($item->getProductType() == 'course'
                        && !in_array($item->getProductId(), $myCourses)
                    ) {
                        array_push($myCourses, $item->getProductId());
                    }
                }
            }
        }
        $group = $this->groupInterface->getById($this->getCurrentGroupId());
        if($group->getCode()=='Rvp')
        {
            $myCourses[] = 5;                                        
        }
        return $myCourses;
    }
}