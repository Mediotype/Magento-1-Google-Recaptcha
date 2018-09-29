<?php
/**
 * Class Mediotype_Recaptcha_Block_Recaptcha
 */
class Mediotype_Recaptcha_Block_Recaptcha extends Mage_Core_Block_Template
{
    /**
     * @return bool
     */
    public function getIsEnabled()
    {
        return $this->getRecaptchaHelper()->getEnabled();
    }

    /**
     * @return string
     */
    public function getSiteKey()
    {
        return $this->getRecaptchaHelper()->getSiteKey();
    }

    /**
     * @return string
     */
    public function getJsPath()
    {
        return $this->getRecaptchaHelper()->getJsPath();
    }

    /**
     * @return string
     */
    public function getSetUrl()
    {
        return $this->getRecaptchaHelper()->getSetUrl();
    }
    
    /**
     * @return Mediotype_Recaptcha_Helper_Data
     */
    public function getRecaptchaHelper()
    {
        return $this->helper('mediotype_recaptcha');
    }
}