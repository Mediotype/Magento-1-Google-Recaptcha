<?php
/**
 * Class Mediotype_Recaptcha_Helper_Data
 */
class Mediotype_Recaptcha_Helper_Data extends Mediotype_Core_Helper_Abstract
{

    /**
     * Config paths for using throughout the code
     */
    const RECAPTCHA_ENABLED  = 'mediotype_recaptcha/general/enabled';
    const RECAPTCHA_DEBUG  = 'mediotype_recaptcha/general/debug';
    const RECAPTCHA_SESSION = 'hrresponse';

    /**
     * Check if extension is enabled
     * return Boolean
     */
    public function getEnabled()
    {
        return Mage::getStoreConfig($this::RECAPTCHA_ENABLED);
    }

    /**
     * Check if debug is enabled
     * @return Boolean
     */
    public function getDebugMode()
    {
        return Mage::getStoreConfig($this::RECAPTCHA_DEBUG);
    }

    /**
     * pull public key
     * return String
     */
    public function getSiteKey()
    {
        return Mage::getStoreConfig('mediotype_recaptcha/general/site_key');
    }

    /**
     * @return string
     */
    public function getSetUrl()
    {
        return Mage::getUrl('hr/index/set',array('_secure'=>true));
    }

    /**
     * @return string
     */
    public function getSendUrl()
    {
        return Mage::getUrl('hr/index/send',array('_secure'=>true));
    }

    /**
     * Path to google recaptcha js
     * @return string
     */
    public function getJsPath()
    {
        return 'https://www.google.com/recaptcha/api.js';
    }

    /**
     * URL for verification
     * @return string
     */
    public function getSubmitUrl()
    {
        return 'https://www.google.com/recaptcha/api/siteverify';
    }

    /**
     * @param $recaptcha
     * @return array
     */
    public function sendData($recaptcha)
    {
        if(!$this->getEnabled()) {
            return array(
                "success"=>false,
                "error-codes"=>"not enabled"
            );
        }
        $data = array(
            'secret' => Mage::helper('core')->decrypt($this->getSecret()),
            'response' => $recaptcha,
            'remoteip' => Mage::helper('core/http')->getRemoteAddr()
        );

        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $this->getSubmitUrl());
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            $result = curl_exec($ch);
            curl_close($ch);
            return json_decode($result,true);
        } catch (Exception $e) {
            Mage::logException($e);
            return array(
                "success"=>false,
                "error-codes"=>$e->getMessage()
            );
        }
    }

    /**
     * pull secret key
     * return String
     */
    protected function getSecret()
    {
        return Mage::getStoreConfig('mediotype_recaptcha/general/secret');
    }

}
