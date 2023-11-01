<?php

namespace Bdollarapps\MagemeForms\Service;

use Bdollarapps\MagemeForms\Helper\Data;
use Psr\Log\LoggerInterface;
use Twilio\Rest\Client;
use Twilio\Rest\ClientFactory;
use Magento\Store\Model\StoreManagerInterface;

class TokeniseSMS
{
    /** @var ClientFactory */
    private $clientFactory;

    /** @var LoggerInterface */
    private $logger;

    /**
     * @var Data
     */
    protected $helperData;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    public function __construct(
        ClientFactory $clientFactory,
        LoggerInterface $logger,
        Data $helperData,
        StoreManagerInterface $storeManager
    )
    {
        $this->clientFactory = $clientFactory;
        $this->logger = $logger;
        $this->helperData = $helperData;
        $this->storeManager = $storeManager;

    }

    public function sendTokeniseSMS($toNumber, $lang, $url)
    {
        $client = $this->prepareTwilioApi();
        $params = [
            'from' => $this->helperData->GetTwilioPhoneNumber(),
            'body' => $this->addExpire($this->getBody($url, $lang)),
        ];

        try {
            $client->messages->create($toNumber, $params);
            $this->logger->critical('SMS message', ['toNumber' => $toNumber]);
        } catch (\Exception $e) {
            $this->logger->critical('Error message', ['exception' => $e]);
        }
    }

    protected function getBody($url, $lang)
    {
        $url = $this->storeManager->getStore()->getBaseUrl('direct_link') . $url;
        return str_replace('%survery_link', $url, $this->helperData->getSmsFormat($lang));
    }

    protected function addExpire($format)
    {
        return str_replace('%expery_threshold', $this->helperData->getExpireMinutes(), $format);
    }

    /**
     * @return Client
     */
    protected function prepareTwilioApi()
    {
        $client = $this->clientFactory->create([
            'username' => $this->helperData->getAccountSID(),
            'password' => $this->helperData->getAuthToken()
        ]);

        return $client;
    }
}

