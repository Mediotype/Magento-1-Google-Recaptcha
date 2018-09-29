<?php

/**
 * Class Mediotype_Recaptcha_IndexController
 */

class Mediotype_Recaptcha_IndexController extends Mage_Core_Controller_Front_Action {
    /**
     * Redirect users who somehow get here
     */
	public function indexAction() {
		$this->_redirect('no-route');
	}

    /**
     * send data to google recaptcha
     * return 200 or 400 response
     */
    public function sendAction() {
        $helper = $this->getRecaptchaHelper();
        if(!$helper->getEnabled()) {
            $this->_redirect('no-route');
            return;
        }
        $responseArray = array();
        //make sure we have data before we do anything
        $session = $this->getCustomerSession();
        $recaptcha = $session->getData($helper::RECAPTCHA_SESSION);
        if($recaptcha == null) {
            $responseArray['code'] = 400;
            $this->getResponse()->setBody(json_encode($responseArray));
            return;
        }
        //send data to google
        //response header is set by google
        $helper->sendData($recaptcha);
    }

    /**
     * save recaptcha data
     */
    public function setAction() {
        $helper = $this->getRecaptchaHelper();
        if($helper->getEnabled()) {
            $params = $this->getRequest()->getParams();
            if($params['response_data']) {
                $this->getCustomerSession()->setData($helper::RECAPTCHA_SESSION,$params['response_data']);
            }
        }
        else {
            $this->_redirect('no-route');
        }
    }

    /**
     * @return Mediotype_Recaptcha_Helper_Data
     */
    public function getRecaptchaHelper()
    {
        return Mage::helper('mediotype_recaptcha');
    }

    /**
     * @return Mage_Customer_Model_Session
     */
    protected function getCustomerSession() {
        return Mage::getSingleton('customer/session');
    }
	
}