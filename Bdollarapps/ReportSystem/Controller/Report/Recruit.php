<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Bdollarapps\ReportSystem\Controller\Report;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\View\Result\PageFactory;
use Bdollarapps\ReportSystem\Service\Authencate;

class Recruit implements HttpGetActionInterface
{

    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var Authencate
     */
    protected $authencate;

    /**
     * Constructor
     *
     * @param PageFactory $resultPageFactory
     */
    public function __construct(PageFactory $resultPageFactory, Authencate $authencate)
    {
        $this->resultPageFactory = $resultPageFactory;
        $this->authencate = $authencate;
    }

    /**
     * Execute view action
     *
     * @return ResultInterface
     */
    public function execute()
    {
        if (!$this->authencate->isAuth()) {
            return $this->authencate->redirecLogin();
        }
        return $this->resultPageFactory->create();
    }
}

