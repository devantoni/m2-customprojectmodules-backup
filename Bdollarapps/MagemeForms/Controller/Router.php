<?php

namespace Bdollarapps\MagemeForms\Controller;

use Magento\Framework\App\RouterInterface;
use Bdollarapps\MagemeForms\Model\ResourceModel\TokeniseLink\Collection as TokeniseLinkCollection;
use Bdollarapps\MagemeForms\Model\ResourceModel\TokeniseLink\CollectionFactory as TokeniseLinkCollectionFactory;
use Bdollarapps\MagemeForms\Helper\Data;
use Magento\Framework\App\ActionFactory;

class Router implements RouterInterface
{
    /**
     * @var TokeniseLinkCollectionFactory
     */
    protected $tokeniseLinkCollectionFactory;

    /**
     * @var ActionFactory
     */
    protected $actionFactory;

    /**
     * @var Data
     */
    private $helper;

    public function __construct(
        TokeniseLinkCollectionFactory $tokeniseLinkCollectionFactory,
        ActionFactory $actionFactory,
        Data $helper
    ) {
        $this->tokeniseLinkCollectionFactory = $tokeniseLinkCollectionFactory;
        $this->actionFactory = $actionFactory;
        $this->helper = $helper;
    }

    /**
     * Validate and Match Cms Page and modify request
     *
     * @param \Magento\Framework\App\RequestInterface $request
     * @return \Magento\Framework\App\ActionInterface|null
     */
    public function match(\Magento\Framework\App\RequestInterface $request)
    {
        if (!$this->helper->getIsEnabled()) {
            return null;
        }

        $identifier = trim($request->getPathInfo(), '/');

        /** @var TokeniseLinkCollection $collection */
        $collection = $this->tokeniseLinkCollectionFactory->create();
        $collection->addFieldToFilter('tokenise_url', ['eq' => $identifier]);

        if ($collection->getSize()) {
            $form = $collection->getFirstItem();
            $createDate = strtotime($form->getCreateTime());
            $currDate = strtotime('now');
            $diff = round(abs($currDate - $createDate) / 60,2);
            if ($diff > $this->helper->getExpireMinutes()) {
                return null;
            }
            $cid = $form->getCustomerId(); // RVP or Agent ID
            $lang = $form->getLang();
            $type = $form->getType();

            $formId = $this->helper->getTokeniseFormId();

            $request->setModuleName('survery')->setControllerName('form')->setActionName('index')
                ->setParam('lang', $lang) // Language
                ->setParam('rvp_agent_id', $cid); // Set RVP or Agent ID as Parameter

            if($type == 'pratice')
            {
                $request->setParam('type',$type);
            }
            return $this->actionFactory->create(\Magento\Framework\App\Action\Forward::class);
        }

        return null;
    }
}
