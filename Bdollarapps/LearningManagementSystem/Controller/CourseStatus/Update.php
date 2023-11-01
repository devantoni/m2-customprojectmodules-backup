<?php

namespace Bdollarapps\LearningManagementSystem\Controller\CourseStatus;
class Update extends \Webkul\LearningManagementSystem\Controller\CourseStatus\Update
{
	public function execute()
    {
        $resultJson = $this->resultJsonFactory->create();
        $error = false;
        
        $customerId = $this->helper->getCurrentCustomerId();
        $customercourses = $this->helper->getCurrentCustomerCourseIds();
        $formData = $this->getRequest()->getParams();
        $completeStatus = isset($formData['button'])?true:false;
        try {
            try{
               $contentIds = json_decode($formData['contentId']);

            }catch(\Exception)
            {
                $contentIds = [$formData['contentId']];
            }

            $courseStatusCollection = $this->courseStatusFactory->create()
                                        ->getCollection()
                                        ->addFieldToFilter('course_id', $formData['courseId'])
                                        ->addFieldToFilter('content_id', ['in'=>$contentIds])
                                        ->addFieldToFilter('customer_id', $customerId);
            

            if (!$courseStatusCollection->getSize() && in_array($formData['courseId'], $customercourses)) {
                foreach($contentIds as $_id)
                {
                    $courseStatusModel = $this->courseStatusFactory->create();
                    $courseStatusModel->setCustomerId($customerId);
                    $courseStatusModel->setContentId($_id);
                    $courseStatusModel->setCourseId($formData['courseId']);
                    $courseStatusModel->save();    
                }   
                if($completeStatus)
                {
                    $courseStatusModel = $this->courseStatusFactory->create();
                    $courseStatusModel->setCustomerId($customerId);
                    $courseStatusModel->setContentId(0);
                    $courseStatusModel->setCourseId($formData['courseId']);
                    $courseStatusModel->save();
                }

            }else if(in_array($formData['courseId'], $customercourses)){
                $currentIds = [];
                foreach($courseStatusCollection as $_course)
                {
                    $currentIds[] = $_course->getContentId();
                }
                foreach($contentIds as $_id)
                {
                    if(!in_array($_id,$currentIds))
                    {
                         $courseStatusModel = $this->courseStatusFactory->create();
                         $courseStatusModel->setCustomerId($customerId);
                         $courseStatusModel->setContentId($_id);
                         $courseStatusModel->setCourseId($formData['courseId']);
                         $courseStatusModel->save();    
                    }
                }
                if($completeStatus && !in_array(0,$currentIds))
                {
                    $courseStatusModel = $this->courseStatusFactory->create();
                    $courseStatusModel->setCustomerId($customerId);
                    $courseStatusModel->setContentId(0);
                    $courseStatusModel->setCourseId($formData['courseId']);
                    $courseStatusModel->save();
                }
            }

            $message = __('Course Status update successfully.');
        } catch (\Exception $e) {
            $error = true;
            $message = $e->getMessage();
        }

        $resultJson = $this->resultJsonFactory->create();
        $resultJson->setData(
            [
                'message' => $message,
                'error' => $error,
            ]
        );
    
        return $resultJson;
    }
}