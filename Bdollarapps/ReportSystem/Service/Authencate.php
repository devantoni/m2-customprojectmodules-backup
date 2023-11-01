<?php
namespace Bdollarapps\ReportSystem\Service;

use Magento\Framework\Controller\Result\Redirect;
use Magento\Authorization\Model\UserContextInterface;

class Authencate
{
    /**
     * @var UserContextInterface
     */
    protected $userContext;

    /**
     * @var Redirect
     */
    protected $redirect;

    public function __construct(
        UserContextInterface $userContext,
        Redirect $redirect
    )
    {
        $this->userContext = $userContext;
        $this->redirect = $redirect;
    }

    public function isAuth()
    {
        if ($this->userContext->getUserId()) {
            return true;
        }

        return false;
    }

    public function redirecLogin()
    {
        $redirect = $this->redirect;
        return $redirect->setPath('customer/account/login');
    }
}
