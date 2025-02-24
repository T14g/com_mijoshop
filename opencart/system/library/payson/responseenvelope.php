<?php
/*
* @package      MijoShop
* @copyright    2009-2016 Miwisoft LLC, miwisoft.com
* @license      GNU/GPL http://www.gnu.org/copyleft/gpl.html
* @license      GNU/GPL based on AceShop www.joomace.net
*/

// No Permission
defined('_JEXEC') or die('Restricted access');

class ResponseEnvelope {
    protected $ack;
    protected $timestamp;
    protected $errors;

    public function __construct($responseData) {
        $this->ack = $responseData["responseEnvelope.ack"];
        $this->timestamp = $responseData["responseEnvelope.timestamp"];
        $this->errors = $this->parseErrors($responseData);
    }

    public function wasSuccessful() {
        return $this->ack === "SUCCESS";
    }

    public function getErrors() {
        return $this->errors;
    }

    public function __toString() {
        return "ack: " . $this->ack . "\n" .
               "timestamp: " . $this->timestamp . "\n";
    }

    private function parseErrors($output) {
        $errors = array();

        $i = 0;
        while(isset($output[sprintf("errorList.error(%d).message", $i)])){
            $errors[$i] = new PaysonApiError(
                $output[sprintf("errorList.error(%d).errorId", $i)],
                $output[sprintf("errorList.error(%d).message", $i)],
                    isset($output[sprintf("errorList.error(%d).parameter", $i)]) ?
                            $output[sprintf("errorList.error(%d).parameter", $i)] : null
            );
            $i++;
        }

        return $errors;
    }
}
