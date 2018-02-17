<?php

/**
 * TestConfiguration.
 *
 * This module contains TestConfiguration class, supporting global data access.
 *
 * @package test_config.php
 * @author xbenes49
 * @copyright Martin Benes (c) 2018
 */

include 'defs.php';
include 'io.php';

/**
 * TestConfiguration
 *
 * This class represents configuration of the run. It contains and processes
 * arguments of environment given and is able to print statistics.
 * @package test_config.php
 * @subpackage TestConfiguration
 */
class TestConfiguration
{

  /* ---------- DATA --------- */
  /**
   * Directory with tests.
   * @var string
   * @access private
   */
  private $test_dir = ".";
  /**
   * Tests will be searched in test directory recursively.
   * @var string
   * @access private
   */
  private $test_dir_recursive = False;
  /**
   * Map of files, given as arguments from terminal:
   * parse-script is --parse-script=file, int-script is --int-script=file
   * @var array
   * @access private
   */
  private $files = array( "parse-script" => "./parse.php", "int-script" => "./intepret.py" );
  /**
   * Map of marks of given arguments.
   * @var array
   * @access private
   */
  private $given = array( "parse-script" => False, "int-script" => False );
  /* ------------------------- */

  /* ---------- GETTERS --------- */
  public function GetParse() { return $this->test_dir . '/' . $this->files["parse-script"]; }
  public function GetInterpret() { return $this->test_dir . '/' . $this->files["int-script"]; }

  /**
   * Constructor of Configuration object.
   *
   * This method is create when instatiating Configuration object.
   * @access public
   * @param array args          Argv from terminal.
   */
  public function __construct($args)
  {
    // every argument
    foreach(array_slice($args,1) as $a)
    {

      // help
      if($a == "--help") { $this->PrintHelp(); }

      // --recursive
      if($a == "--recursive") { $this->test_dir_recursive = True; }


      // --parse-script
      elseif( preg_match('/--parse-script=.+$/', $a) )
      {
        // check if multiple
        if( $this->given["parse-script"] ) throw new Exception("Multiple argument ".$a);
        // set
        $this->given["parse-script"] = True;
        $this->files["parse-script"] = preg_split('/=/', $a)[1];
      }

      // --parse-script
      elseif( preg_match('/--int-script=.+$/', $a) )
      {
        // check if multiple
        if( $this->given["int-script"] ) throw new Exception("Multiple argument ".$a);
        // set
        $this->given["int-script"] = True;
        $this->files["int-script"] = preg_split('/=/', $a)[1];
      }

      // unknown argument
      else throw new Exception("Unknown argument ".$a);
    }

  }

  /**
   * Prints help. Always throws HelpException.
   * @access public
   */
  public function PrintHelp()
  {
    echo "Printing help!\n";
    throw new HelpException();
  }
}

?>
