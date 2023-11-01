<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Bdollarapps\ReportSystem\Controller\Report;

use Magento\Framework\App\Action\Context;
use Magento\Framework\View\LayoutFactory;
use WeProvide\Dompdf\Controller\Result\Dompdf;
use WeProvide\Dompdf\Controller\Result\DompdfFactory;

class AssetsummaryExport extends \Magento\Framework\App\Action\Action
{

    protected $dompdfFactory;
    protected $layoutFactory;

    /**
     * Constructor
     *
     * @param Context $context
     * @param DompdfFactory $dompdfFactory
     *
     */
    public function __construct(
        Context $context,
        DompdfFactory $dompdfFactory,
        LayoutFactory $layoutFactory
    ) {
        $this->dompdfFactory = $dompdfFactory;
        $this->layoutFactory = $layoutFactory;
        parent::__construct($context);
    }


    /**
     * Execute view action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $block = $this->layoutFactory->create()->createBlock('Bdollarapps\ReportSystem\Block\Report\Assetsummary');
        $image = $this->getRequest()->getParam('img');
        $results = $block->getAgentWiseData();
        $list = $block->getAgentList();
        $data = [] ;
        foreach($results->getData() as $key => $record)
        {
            $data['result'][$list[$record['rvp_agent_id']]]['ira']= $record['tot_ira_self']+$record['tot_ira_spouse'];
            $data['result'][$list[$record['rvp_agent_id']]]['rollover']= $record['tot_rollover_self']+$record['tot_rollover_spouse'];
            $data['result'][$list[$record['rvp_agent_id']]]['annuity']= $record['tot_annuity_self']+$record['tot_annuity_spouse'];
            $data['result'][$list[$record['rvp_agent_id']]]['inheritance']= $record['tot_inheritance'];
            $data['result'][$list[$record['rvp_agent_id']]]['savings']= $record['tot_savings'];
        }
	    $data['img'] = $image;

        $block->setData($data);
        $block->setTemplate('Bdollarapps_ReportSystem::report/assetsummarypdf.phtml');
        
    
        $response = $this->dompdfFactory->create();
        $response->setFileName('Assets Summary.pdf');
	    $response->setData($block->toHtml());

        return $response;
    }

}

