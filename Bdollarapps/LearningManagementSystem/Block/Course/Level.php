<?php
/**
 * Webkul Software.
 *
 * @category  Webkul
 * @package   Webkul_LearningManagementSystem
 * @author    Webkul
 * @copyright Copyright (c) Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */
namespace Bdollarapps\LearningManagementSystem\Block\Course;

use Webkul\LearningManagementSystem\Helper\Data;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\App\RequestInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\View\Element\Template;

class Level extends Template
{

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Webkul\LearningManagementSystem\Model\CourseStatusFactory $courseStatusFactory,
        \Magento\Customer\Model\Session $session,
        array $data
    ) {
    
        parent::__construct($context, $data);
        $this->customerSession = $session;
        $this->courseStatusFactory = $courseStatusFactory;
    }


    public function allowCourse()
    {
    
        $id = $this->customerSession->getCustomerId();
	    $courseStatusCollection = $courseStatusFactory->create()
                                    ->getCollection()
                                    ->addFieldToFilter('course_id', 5)
                                    ->addFieldToFilter('content_id', 1)
                                    ->addFieldToFilter('customer_id', $id);
 		if (!$courseStatusCollection->getSize()) {
 			return false;
 		}else {
 			return true;
 		}
    }
}
