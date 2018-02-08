<?php

/**
 * Types definition.
 *
 * This module contains implementation of types (classes), used throughout
 * the whole program. It includes I/O OOP interface and model interface.
 * It also contains some constants (regexes to match from).
 *
 * @package defs.php
 * @author xbenes49
 * @copyright Martin Benes (c) 2018
 */



/* =========================== IO ============================= */
/**
 * File class.
 *
 * This class represents file descriptor.
 * @package defs.php
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
   * This methods opens file, given by name, as type ('r', 'w', ...).
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

/* ============================================================== */






/* ========================== MODEL ============================= */

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
$const_regex = '/(int@[-+]{0,1}[0-9][0-9]*)|(string@[^\s\b#]*)|(bool@((true)|(false)))/';
// constant is    ^ integer              or  ^ string       or  ^ bool

/**
 * Regex, matching identifiers.
 * @var string
 */
$id_regex = '/[-a-zA-Z_$&%*]+/';
// id consists of ^ these

/**
 * Regex, matching variables.
 * @var string
 */
$var_regex = '/((LF)|(GF)|(TF))@[-a-zA-Z_$&%*]+/';
// variable has ^ frame and    ^ at and ^ id

/**
 * Regex, matching types.
 * @var string
 */
$type_regex = '/(bool)|(int)|(string)/';

/* =========================================================================== */


/* ============================= Configuration ============================= */

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
      elseif( preg_match('/--stat=".*"/', $a) )
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
