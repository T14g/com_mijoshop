<?php
/*
* @package      MijoShop
* @copyright    2009-2016 Miwisoft LLC, miwisoft.com
* @license      GNU/GPL http://www.gnu.org/copyleft/gpl.html
* @license      GNU/GPL based on AceShop www.joomace.net
*/

// No Permission
defined('_JEXEC') or die('Restricted access');

class PaysonApiError {
    protected $errorId;
    protected $message;
    protected $parameter;

    public function __construct($errorId, $message, $parameter = null){
        $this->errorId = $errorId;
        $this->message = $message;
        $this->parameter = $parameter;
    }

    public function getMessage() {
        return $this->message;
    }

    public function getParameter() {
        return $this->parameter;
    }

    public function getErrorId() {
        return $this->errorId;
    }

    public function __toString() {
        return "ErrorId: " . $this->getErrorId() .
               " Message: " . $this->getMessage() .
               " Parameter: " . $this->getParameter();
    }
}
