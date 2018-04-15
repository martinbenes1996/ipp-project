<?php

/**
 * Types definition.
 *
 * This module contains implementation of model interface.
 * It also includes some constants (regexes to match from).
 *
 * @package parse_model.php
 * @author xbenes49
 * @copyright Martin Benes (c) 2018
 */

/* ========================== MODEL ============================= */

include 'defs.php';

/**
 * Argument class.
 *
 * This class represents one argument of instruction.
 * @package defs.php
 * @subpackage Model
 */
class Argument
{

  /* ---------- DATA --------- */
  /**
   * Data of argument.
   * @var string
   * @access private
   */
  private $data = "";

  /**
   * Type of argument.
   * @var string
   * @access private
   */
  private $type = "";

  /**
   * Order of argument in instruction.
   * @var int
   * @access private
   */
  private $num = 1;
  /* ------------------------- */


  /**
   * Constructor of Argument object.
   *
   * This method is create when instatiating Argument object.
   * @access public
   * @param string data      Data of argument.
   * @param string type      Type of argument.
   * @param int num          Order of argument in instruction.
   */
  public function __construct($data, $type, $num)
  {
    $this->data = $data;
    $this->type = $type;
    $this->num = $num;
  }

  /**
   * Transcribes Argument to XML.
   *
   * This method returns string XML representation.
   * @access public
   * @returns string          XML representation.
   */
  public function toXML()
  {
    return ( $this->type != "" ) ?
      ("<arg".$this->num." type=\"".$this->type."\">" .
          $this->data .
       "</arg".$this->num.">\n") : "";
  }
}


/**
 * Instruction class.
 *
 * This class represents one instruction.
 * @package defs.php
 * @subpackage Model
 */
class Instruction
{

  /* ---------- DATA --------- */
  /**
   * Instruction name.
   * @var string
   * @access private
   */
  private $opcode = "";

  /**
   * First argument.
   * @var Argument
   * @access private
   */
  private $arg1 = NULL;

  /**
   * Second argument.
   * @var Argument
   * @access private
   */
  private $arg2 = NULL;

  /**
   * Third argument.
   * @var Argument
   * @access private
   */
  private $arg3 = NULL;
  /* ------------------------- */

  /**
   * Constructor of Instruction object.
   *
   * This method is create when instatiating Instruction object.
   * @access public
   * @param string opcode    Instruction name.
   * @param Argument arg1    First argument.
   * @param Argument arg2    Second argument.
   * @param Argument arg3    Third argument.
   */
  public function __construct($opcode, $arg1 = NULL, $arg2 = NULL, $arg3 = NULL)
  {
      $this->opcode = $opcode;
      $this->arg1 = $arg1;
      $this->arg2 = $arg2;
      $this->arg3 = $arg3;
  }

  /**
   * Setter of order of instruction.
   * @access public
   * @param int order       Order of the instruction in program.
   */
  public function setOrder($order)
  {
    $this->order = $order;
  }

  /**
   * Transcribes Instruction to XML.
   *
   * This method returns string XML representation.
   * @access public
   * @returns string          XML representation.
   */
  public function toXML()
  {
    return "\t<instruction order=\"" . $this->order . "\" ".
                         "opcode=\"" . $this->opcode ."\">\n".
        (($this->arg1) ? "\t\t".$this->arg1->toXML() : "") .
        (($this->arg2) ? "\t\t".$this->arg2->toXML() : "") .
        (($this->arg3) ? "\t\t".$this->arg3->toXML() : "") .
      "\t</instruction>\n";

  }
}

/* ============================= REGEX CONSTANTS ============================= */

/**
 * Regex, matching constants (int, string, bool).
 * @var string
 */
$const_regex = '/^(int@[-+]{0,1}[0-9][0-9]*)|(string@[^\s\b#]*)|(bool@((true)|(false)))$/';
// constant is    ^ integer              or  ^ string       or  ^ bool

/**
 * Regex, matching identifiers.
 * @var string
 */
$id_regex = '/^[-a-zA-Z_$&%*][-a-zA-Z0-9_$&%*]*$/';
// id consists of ^ these

/**
 * Regex, matching variables.
 * @var string
 */
$var_regex = '/^((LF)|(GF)|(TF))@[-a-zA-Z_$&%*][-a-zA-Z0-9_$&%*]*$/';
// variable has ^ frame and    ^ at and ^ id

/**
 * Regex, matching types.
 * @var string
 */
$type_regex = '/^(bool)|(int)|(string)$/';

/* =========================================================================== */


/* ============================= Configuration ============================= */

/**
 * Configuration
 *
 * This class represents configuration of the run. It contains and processes
 * arguments of environment given and is able to print statistics.
 * @package defs.php
 * @subpackage Configuration
 */
class Configuration
{

  /* ---------- DATA --------- */
  /**
   * Filename of file to print statistics into. Must be present,
   * when statistics are enabled.
   * @var string
   * @access private
   */
  private $stat_file = "";
  /**
   * Map of flags, given as arguments from terminal:
   * stat is --stat="file", loc is --loc, comments is --comments
   * @var array
   * @access private
   */
  private $enabled = array( "stat" => False, "loc" => False, "comments" => False );
  /**
   * Key of the key, that appeared first in argv (will be first in statistics file).
   * @var string
   * @access private
   */
  private $first = "";
  /* ------------------------- */

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


      // --stat
      elseif( preg_match('/--stat=".+"/', $a) )
      {
        // check if multiple
        if( $this->enabled["stat"] ) throw new Exception("Multiple argument ".$a);
        // set
        $this->enabled["stat"] = True;
        $this->stat_file = preg_split('/"/', $a)[1];
      }


      // --comments
      elseif( $a == "--comments" )
      {
        // check if multiple
        if( $this->enabled["comments"] ) throw new Exception("Multiple argument ".$a);
        // set
        $this->enabled["comments"] = True;
        if($this->first == "") $this->first = "comments";
      }

      // --loc
      elseif( $a == "--loc" )
      {
        // check if multiple
        if( $this->enabled["loc"] ) throw new Exception("Multiple argument ".$a);
        // set
        $this->enabled["loc"] = True;
        if($this->first == "") $this->first = "loc";
      }

      // unknown argument
      else throw new Exception("Unknown argument ".$a);
    }
    // no --stat, but --loc or --comment
    if(!$this->enabled["stat"] && $this->first != "")
      throw new Exception("Not given argument --stat=\"file\"");
    // -- stat, but no --loc or --comment
    if($this->enabled["stat"] && $this->first == "")
      throw new Exception("Not given any of arguments --loc or --comment");
  }

  /**
   * Prints statistics to the statistics file.
   * @access public
   * @param string loc        LOC count.
   * @param string comments   Comments count.
   */
  public function PrintStatistics($loc, $comments)
  {
    if(!$this->enabled["stat"]) return;

    $stat = new FileWriter($this->stat_file);
    if($this->first == "loc")
    {
      $stat->write($loc."\n");
      if($this->enabled["comments"]) $stat->write($comments."\n");
    }
    else
    {
      $stat->write($comments."\n");
      if($this->enabled["loc"]) $stat->write($loc."\n");
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
