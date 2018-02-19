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

include 'test_config.php'; // TestConfiguration
include 'test_generator.php'; // HTMLGenerator


class TestResult
{
  private $parse_out = "";
  private $parse_err = "";
  private $parse_code = 0;
  private $int_out = "";
  private $int_err = "";
  private $int_code = 0;
  private $test_name = "";
  private $err_msg = "";
  private $stat = True;

  public function SetParseOut($out) { $this->parse_out = $out; }
  public function SetParseErr($err) { $this->parse_err = $err; }
  public function SetParseCode($code) { $this->parse_code = $code; }
  public function SetIntOut($out) { $this->int_out = $out; }
  public function SetIntErr($err) { $this->int_err = $err; }
  public function SetIntCode($code) { $this->int_code = $code; }
  public function SetTestName($name) { $this->test_name = $name; }
  public function SetErrorMessage($msg) { $this->err_msg = $msg; }
  public function SetStatus($stat) { $this->stat = $stat; }

  public function GetParseOut() { return $this->parse_out; }
  public function GetParseErr() { return $this->parse_err; }
  public function GetParseCode() { return $this->parse_code; }
  public function GetIntOut() { return $this->int_out; }
  public function GetIntErr() { return $this->int_err; }
  public function GetIntCode() { return $this->int_code; }
  public function GetTestName() { return $this->test_name; }
  public function GetErrorMessage() { return $this->err_msg; }
  public function GetStatus() { return $this->stat; }


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
   * Returns TestResult object.
   * @access public
   * @param string program  Program filename.
   */
  public function RunTest($name)
  {
    // result record
    $r = new TestResult();


    /* ------------- PARSE --------------- */
    // tmp files
    $pout = new TmpFile();
    $perr = new TmpFile();

    // run parse
    exec('php '.$this->parse.' < '.$name
                          .' > '.$pout->GetName()
                          .' 2> '.$perr->GetName(),
        $out_str, $pcode);

    // parse
    $r->SetParseOut($pout->read());
    $r->SetParseErr($perr->read());
    $r->SetParseCode(intval($pcode));


    /* ------------- INTERPRET ------------- */
    // tmp files
    $iout = new TmpFile();
    $ierr = new TmpFile();

    // run interpret
    exec('python3.5 '.$this->interpret.' --source='.$pout->GetName()
                              .' > '.$iout->GetName()
                              .' 2> '.$ierr->GetName(),
        $out_str, $icode);

    // parse
    $r->SetIntOut($iout->read());
    $r->SetIntErr($ierr->read());
    $r->SetIntCode(intval($icode));


    /* ------------------------------------- */
    return $r;
  }

}

class TestSet
{
  private $tests = array();

  public function __construct($tests)
  {
    $this->tests = $tests;
  }

  public function Launch($program)
  {
    $generator = new HTMLGenerator();
    $a = array();
    foreach($this->tests as $t)
    {
      $result = $program->RunTest( $t.'.src' );
      $result->SetTestName( $t.'.src' );

      // parse error
      if($result->GetParseCode() != 0)
      {
        $result->SetIntCode("-");
        $result->SetErrorMessage( $result->GetParseErr() );
        $result->SetStatus(False);
      }
      else
      {
        // interpret error
        if($result->GetIntCode() != 0)
        {
          $result->SetErrorMessage( $result->GetIntErr() );
          $result->SetStatus(False);
        }
        // OK
        else
        {
          $result->SetErrorMessage("-");
          $result->SetStatus(True);
        }
      }

      $generator->AddTest($result);

    }

    return $generator;
  }


}
