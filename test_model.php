<?php

/**
 * Test model definition.
 *
 * This module contains implementation of model for test.php.
 *
 * @package test_model.php
 * @author xbenes49
 * @copyright Martin Benes (c) 2018
 */

include 'test_config.php';


class Result
{
  private $parse_out = "";
  private $parse_err = "";
  private $parse_code = 0;
  private $int_out = "";
  private $int_err = "";
  private $int_code = 0;

  private $test_name = "";

  public function SetParseOut($out) { $this->parse_out = $out; }
  public function SetParseErr($err) { $this->parse_err = $err; }
  public function SetParseCode($code) { $this->parse_code = intval($code); }
  public function SetIntOut($out) { $this->int_out = $out; }
  public function SetIntErr($err) { $this->int_err = $err; }
  public function SetIntCode($code) { $this->int_code = intval($code); }
  public function SetTestName($name) { $this->test_name = $name; }

  public function GetParseOut() { return $this->parse_out; }
  public function GetParseErr() { return $this->parse_err; }
  public function GetParseCode() { return $this->parse_code; }
  public function GetIntOut() { return $this->int_out; }
  public function GetIntErr() { return $this->int_err; }
  public function GetIntCode() { return $this->int_code; }
  public function GetTestName() { return $this->test_name; }


}

/**
 * File class.
 *
 * This class represents program.
 * @package test_model.php
 * @subpackage program
 * @abstract
 */
class Program
{
  /* ---------- DATA --------- */
  /**
   * Parse program.
   * @var string
   * @access private
   */
  private $parse = null;
  /**
   * Interpret program.
   * @var string
   * @access private
   */
  private $interpret = null;
  /* ------------------------- */

  /**
   * Constructor of Program class.
   *
   * This method saves program names.
   * @access public
   * @param string parse     Name of parse program.
   * @param string interpret Name of interpret program.
   */
  public function __construct($parse, $interpret)
  {
    $this->parse = $parse;
    $this->interpret = $interpret;
  }

  /**
   * Runs given program using inner parser and interpret.
   * Returns Result object.
   * @access public
   * @param string program  Program filename.
   */
  public function RunTest($name)
  {
    // result record
    $r = new Result();


    /* ------------- PARSE --------------- */
    // tmp files
    $out = new TmpFile();
    $err = new TmpFile();

    // run parse
    exec('./'.$this->parse.' < '.$name
                          .' > '.$out->GetName()
                          .' 2> '.$err->GetName(),
        $out_str, $code);

    // parse
    $r->SetParseOut($out->read());
    $r->SetParseErr($err->read());
    $r->SetParseCode($code);


    /* ------------- INTERPRET ------------- */
    // tmp files
    $out = new TmpFile();
    $err = new TmpFile();

    // run interpret
    exec('./'.$this->interpret.' --source='.$name
                              .' > '.$out->GetName()
                              .' 2> '.$err->GetName(),
        $out_str, $code);

    // parse
    $r->SetIntOut($out->read());
    $r->SetIntErr($err->read());
    $r->SetIntCode($code);


    /* ------------------------------------- */
    return $r;
  }

}
