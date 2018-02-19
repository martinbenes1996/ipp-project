<?php

/**
 * IO definition.
 *
 * This module contains implementation of I/O OOP interface.
 *
 * @package io.php
 * @author xbenes49
 * @copyright Martin Benes (c) 2018
 */

/* =========================== IO ============================= */
/**
 * File class.
 *
 * This class represents file descriptor.
 * @package io.php
 * @subpackage IO
 * @abstract
 */
class File
{

  /* ---------- DATA --------- */
  /**
   * Error stream.
   * @var FileWriter
   * @access protected
   */
  protected $handle = null;
  /* ------------------------- */


  /* --- CONSTRUCTOR, DESTRUCTOR --- */
  /**
   * Constructor of File class.
   *
   * This method opens file, given by name, as type ('r', 'w', ...).
   * It can't be directly called., because the class is abstract.
   * @access protected
   * @param string name      Name of file.
   * @param string type      Type of opening a file.
   */
  protected function __construct($name, $type)
  {
    $this->handle = fopen($name, $type);
  }

  /**
   * Destructor of the File class.
   *
   * This method is called at the end. It closes the file.
   * @access public
   * @param string name      Name of file.
   * @param string type      Type of opening a file.
   */
  public function __destruct()
  {
    fclose($this->handle);
  }
  /*---------------------------------*/


  /**
   * Says, if file reached EOF.
   * @access public
   * @param string name      Name of file.
   * @param string type      Type of opening a file.
   */
  public function eof() { return feof($this->handle); }

}



/**
 * FileReader class.
 *
 * This class represents reading a file.
 * @package defs.php
 * @subpackage IO
 */
class FileReader extends File
{

  /**
   * Constructor of FileReader class.
   * @access public
   * @param string name      Name of file.
   */
  public function __construct($name = 'php://stdin')
  {
    parent::__construct($name, 'r');
  }

  /**
   * Reads one line.
   *
   * This method reads one line out of input file and returns it trimmed.
   * @access public
   * @return string         Input line.
   */
  public function read() { return trim(fgets($this->handle)); }
  public function read_raw() { return fgets($this->handle); }
  public function get()
  {
    $s = "";
    while(($l = $this->read_raw()) != false)
    {
      $s = $s . $l;
    }
    return $s;
  }


}

/**
 * FileWriter class.
 *
 * This class represents reading a file.
 * @package defs.php
 * @subpackage IO
 */
class FileWriter extends File
{
  /**
   * Constructor of FileWriter class.
   * @access public
   * @param string name      Name of file.
   */
  public function __construct($name = 'php://stdout')
  {
    parent::__construct($name, 'w');
  }

  /**
   * Reads one line.
   *
   * This method reads one line out of input file and returns it trimmed.
   * @access public
   * @param string str       Line to write.
   */
  function write($str) { return fputs($this->handle, $str); }
}

class TmpFile
{
  private $name = "";
  private $reader = null;

  public function __construct()
  {
    $this->name = tempnam(".", "");
    $this->reader = new FileReader($this->name);
  }
  public function __destruct()
  {
    unlink($this->name);
  }

  public function get()
  {
    return $this->reader->get();
  }

  public function GetName() { return $this->name; }
}

/* ============================================================== */
