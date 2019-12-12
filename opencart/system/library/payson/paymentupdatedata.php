<?php
/*
* @package      MijoShop
* @copyright    2009-2016 Miwisoft LLC, miwisoft.com
* @license      GNU/GPL http://www.gnu.org/copyleft/gpl.html
* @license      GNU/GPL based on AceShop www.joomace.net
*/

// No Permission
defined('_JEXEC') or die('Restricted access');

class PaymentUpdateData {
    protected $token;
    protected $method;

    public function __construct($token, $method) {
        $this->token = $token;
        $this->method = $method;
    }

    public function getOutput() {
        $output = array();

        $output["token"] = $this->token;
        $output["action"] = PaymentUpdateMethod::ConstantToString($this->method);

        return $output;
    }
}
