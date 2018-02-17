<?php

/**
 * Types definition.
 *
 * This module contains implementation of global definitions,
 * used in both test.php and parse.php.
 *
 * @package defs.php
 * @author xbenes49
 * @copyright Martin Benes (c) 2018
 */

/* ========================== EXCEPTIONS ============================= */

/**
 * HelpException class.
 *
 * This class represents child exception, used, when --help appears.
 * @package defs.php
 * @subpackage Configuration
 */
class HelpException extends Exception
{
  /**
   * Constructor of HelpException object.
   *
   * This method is create when instatiating HelpException object.
   * @access public
   * @param string message      Error message.
   * @param int code            Error code.
   * @param Exception previous  Previous exception.
   */
  public function __construct($message = "", $code = 0, Exception $previous = null) {
    parent::__construct($message, $code, $previous);
  }

  /**
   * Returns string representation of this HelpException object.
   * @access public
   * @returns string      String representation.
   */
  public function __toString() {
    return $this->message;
  }
}

/* ============================================================== */

?>
