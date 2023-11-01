<?php
namespace Bdollarapps\LearningManagementSystem\Observer;

class CatalogProductSaveBefore extends \Webkul\LearningManagementSystem\Observer\CatalogProductSaveBefore
{

	public function checkCourseOptions($courseArray)
    {
        
        foreach ($courseArray as $key => $value) {
            
            if ($value['course_title'] == '') {

                return false;
            }

            if ($value['course_description'] == '') {

                return false;
            }

            if ($value['course_filename'] == '') {

               // return false;
            }
            if ($value['course_type'] == '') {

                return false;
            }
            if ($value['course_preview'] == '') {

                return false;
            }
        }
        return true;
    }
}