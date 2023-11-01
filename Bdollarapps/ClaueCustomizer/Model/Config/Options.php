<?php

namespace Bdollarapps\ClaueCustomizer\Model\Config;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

class Options extends AbstractSource
{

/**
 * Retrieve All options
 *
 * @return array
 */
public function getAllOptions()
  {
    $this->_options = [
        ['label'=>__('Select Contract Level'),'value'=>'none'],
        ['label'=>__('Representative'),'value'=>'Representative'],
        ['label'=>__('Senior Representative'),'value'=>'Senior-Representative'],
        ['label'=>__('District Leader'),'value'=>'District-Leader'],
        ['label'=>__('Regional Leader'),'value'=>'Regional-Leader'],
        ['label'=>__('Senior Regional Leader'),'value'=>'Senior-Regional-Leader'],
    ];

    return $this->_options;
   }
}
