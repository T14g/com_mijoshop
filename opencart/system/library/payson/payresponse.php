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

class PayResponse {
    protected $responseEnvelope;
    protected $token;

    public function __construct($responseData) {
        $this->responseEnvelope = new ResponseEnvelope($responseData);

        if(isset($responseData["TOKEN"])){
            $this->token = $responseData["TOKEN"];
        }
        else {
            $this->token = "";
        }
    }

    public function getResponseEnvelope() {
        return $this->responseEnvelope;
    }

    public function getToken() {
        return $this->token;
    }

    public function __toString() {

        return $this->responseEnvelope->__toString() .
               "token: " . $this->token;
    }
}
