<?php
namespace Bdollarapps\Subscription\App\Router;

use Magento\Framework\App\ActionFactory;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\RouterInterface;
use Magento\Framework\UrlInterface;
use Psr\Log\LoggerInterface;

class CustomRouter implements RouterInterface
{
    /**
     * @var ActionFactory
     */
    protected $actionFactory;

    /**
     * @var UrlInterface
     */
    protected $url;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * CustomRouter constructor.
     *
     * @param ActionFactory $actionFactory
     * @param UrlInterface $url
     * @param LoggerInterface $logger
     */
    public function __construct(
        ActionFactory $actionFactory,
        UrlInterface $url,
        LoggerInterface $logger,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        $this->actionFactory = $actionFactory;
        $this->url = $url;
        $this->logger = $logger;
        $this->scopeConfig = $scopeConfig;

    }

    /**
     * Validate and Match custom routes
     *
     * @param RequestInterface $request
     *
     * @return bool|\Magento\Framework\App\ActionInterface
     */
    public function match(RequestInterface $request)
    {
    
        $identifier = trim($request->getPathInfo(), '/');
        $condition = new \Magento\Framework\DataObject(['identifier' => $identifier, 'continue' => true]);
        $identifier = $condition->getIdentifier();

        // Get the URL from the store configuration
        $url = 'erp/print-invoice-recipt';

        // Check if the URL is set
        if ($url) {
            if (str_contains($identifier, $url)) {
                $request->setModuleName('rvp_management')
                    ->setControllerName('print')
                    ->setActionName('index');

                return $this->actionFactory->create('Magento\Framework\App\Action\Forward');
            }
        }
        return false;
    }
}
