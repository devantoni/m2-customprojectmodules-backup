<?php
namespace Bdollarapps\MagemeForms\Helper;

use Magento\Framework\App\Config\ScopeConfigInterface;

class Data
{
    public const XML_PATH_SURVEY_ENABLED = 'bdollarapps/twilio_sms/enabled';
    public const XML_PATH_SURVEY_ACCOUNT_SID = 'bdollarapps/twilio_sms/account_sid';
    public const XML_PATH_SURVEY_AUTH_TOKEN = 'bdollarapps/twilio_sms/auth_token';
    public const XML_PATH_SURVEY_TWILIO_PHONE_NUMBER = 'bdollarapps/twilio_sms/twilio_phone_number';
    public const XML_PATH_SURVEY_TWILIO_FORMAT = 'bdollarapps/twilio_sms/twilio_format';
    public const XML_PATH_SURVEY_TWILIO_FORMAT_ES = 'bdollarapps/twilio_sms/twilio_format_es';
    public const XML_PATH_SURVEY_TOKENISE_FORM_ID = 'bdollarapps/twilio_sms/tokenise_form_id';
    public const XML_PATH_SURVEY_SURVEY_FORM_ID = 'bdollarapps/twilio_sms/survey_form_id';
    public const XML_PATH_SURVEY_FORM_EXPIRY = 'bdollarapps/twilio_sms/form_expiry';

    protected $scopeConfig;

    public function __construct(

    )
    {

    }

    public function getIsEnabled()
    {
        return $this->getScopeConfig()->isSetFlag(self::XML_PATH_SURVEY_ENABLED);
    }

    public function getAccountSID()
    {
        return $this->getScopeConfig()->getValue(self::XML_PATH_SURVEY_ACCOUNT_SID);
    }

    public function getAuthToken()
    {
        return $this->getScopeConfig()->getValue(self::XML_PATH_SURVEY_AUTH_TOKEN);
    }

    public function GetTwilioPhoneNumber()
    {
        return $this->getScopeConfig()->getValue(self::XML_PATH_SURVEY_TWILIO_PHONE_NUMBER);
    }

    public function getSmsFormat($lang)
    {
        if ($lang == 'SP') {
            return $this->getScopeConfig()->getValue(self::XML_PATH_SURVEY_TWILIO_FORMAT_ES);
        }
        return $this->getScopeConfig()->getValue(self::XML_PATH_SURVEY_TWILIO_FORMAT);
    }

    public function getTokeniseFormId()
    {
        return $this->getScopeConfig()->getValue(self::XML_PATH_SURVEY_TOKENISE_FORM_ID);
    }

    public function getSurveyFormId()
    {
        return $this->getScopeConfig()->getValue(self::XML_PATH_SURVEY_SURVEY_FORM_ID);
    }

    public function getExpireMinutes()
    {
        return $this->getScopeConfig()->getValue(self::XML_PATH_SURVEY_FORM_EXPIRY);
    }

    /**
     * Returns scope config.
     *
     * @return ScopeConfigInterface
     */
    private function getScopeConfig()
    {
        if (null === $this->scopeConfig) {
            $this->scopeConfig = \Magento\Framework\App\ObjectManager::getInstance()->get(ScopeConfigInterface::class);
        }

        return $this->scopeConfig;
    }
}
