<?php

namespace LISE;

/**
 * @author Fernando Morgado(JoMorg)
 * @since 1.2.2
 * Customizes LISE exception handling
 */
class Exception extends \Exception {

    private $_mod = NULL;

    public function __construct() {
        $this->_mod = \cms_utils::get_module('LISE');
        $params = func_get_args();
        $tmp_params = array();
        $newparams = array();

        if (!isset($params[0])) {
            $code = \LISE\Error::UNKOWN;
            # not a typo; (note: we may change the notation for PHP 5.4 -> $newparams = [$code, $code];)
            # we need it twice: once for the Lang string translation and one to display the error code itself
            $newparams[] = $newparams[] = $code;
        } else {
            # a numeric code has been provided instead of a message
            if (is_numeric($params[0])) {
                $code = $params[0];
                # separate additional $params
                $tmp_params = array_slice($params, 1);
                $newparams[] = $newparams[] = $code;
            } else {
                if (count($params) == 1) {
                    # this is just a custom message instead of a real LISE error code: Discrete
                    $code = \LISE\Error::DISCRETE;
                    $newparams[] = $newparams[] = $code;
                    $newparams[] = $params[0];
                } else {
                    # the call has been called with all its parameters filled:
                    # i.e.: throw new \LISE\Exception($msg, $code[, $param1][, $param2]...[, $param(n)])
                    # where the extra params are passed to the <Module>->Lang() method as aditional params        
                    $code = $params[1];
                    # separate additional $params
                    $tmp_params = array_slice($params, 2);
                    $newparams[] = $newparams[] = $code;
                    $newparams[] = $params[0];
                }
            }

            # prevent non existing errors
            if (!\LISE\Error::HasCode($code)) {
                $code = \LISE\Error::Unknown;
                $newparams[] = $newparams[] = $code;
            }
        }

        # we merge any aditional params and pass them to LISE->Lang();  
        $newparams = array_merge($newparams, $tmp_params);
        $msg = call_user_func_array(array($this->_mod, 'Lang'), $newparams) . ' @ ' . $this->file . ' (' . $this->line . ')';
        parent::__construct($msg, (int) $code);
    }

    public function __toString() {
        return '## ' . $this->message . ' ##';
    }

}
