<?php
/*
* @package      MijoShop
* @copyright    2009-2016 Miwisoft LLC, miwisoft.com
* @license      GNU/GPL http://www.gnu.org/copyleft/gpl.html
* @license      GNU/GPL based on AceShop www.joomace.net
*/

// No Permission
defined('_JEXEC') or die('Restricted access');

require_once "responseenvelope.php";

class PaymentUpdateResponse {
    protected $responseEnvelope;

    public function __construct($responseData) {
        $this->responseEnvelope = new ResponseEnvelope($responseData);
    }

    public function getResponseEnvelope() {
        return $this->responseEnvelope;
    }

    public function __toString() {
        return $this->responseEnvelope->__toString();
    }
}

