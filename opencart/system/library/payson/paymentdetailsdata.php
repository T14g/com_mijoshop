<?php
/*
* @package      MijoShop
* @copyright    2009-2016 Miwisoft LLC, miwisoft.com
* @license      GNU/GPL http://www.gnu.org/copyleft/gpl.html
* @license      GNU/GPL based on AceShop www.joomace.net
*/

// No Permission
defined('_JEXEC') or die('Restricted access');

class PaymentDetailsData {
    protected $token;

    public function __construct($token){
        $this->token = $token;
    }

    public function getOutput() {
        $output = array();

        $output["token"] = $this->token;

        return $output;
    }
}
