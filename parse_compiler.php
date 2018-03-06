<?php

/**
 * Analysis implementation.
 *
 * This module contains implementation of lexical and syntactic analysis
 * of IPPcode18 and its transcription to XML. It contains only Compiler
 * class.
 *
 * @package parse_compiler.php
 * @author xbenes49
 * @copyright Martin Benes (c) 2018
 */


include 'io.php'; // FileReader, FileWriter
include 'parse_model.php'; // Argument, Instruction

/**
 * Compiler class.
 *
 * This class implements all the needed operation to make lexical
 * and syntactic analysis of IPPcode18 and its transcription to XML,
 * including creating input and output streams, reading from them,
 * as same as generating XML, independently on any library.
 *
 * @package parse_compiler.php
 * @subpackage Compiler
 * @uses FileReader, FileWriter
 */
class Compiler
{
  /* ======================== DATA ========================== */

  /**
   * In stream.
   * @var FileReader
   * @access private
   */
  private $in = null;

  /**
   * Out stream.
   * @var FileWriter
   * @access private
   */
  private $out = null;

  /**
   * Count of lines of code.
   * @var int
   * @access private
   */
  private $loc = 0;

  /**
   * Count of lines with comment.
   * @var int
   * @access private
   */
  private $comments = 0;


  /* -------- GETTERS -------- */
  /**
   * Returns LOC read.
   * @access public
   * @return int        LOC.
   */
  public function getLOC() { return $this->loc; }

  /**
   * Returns count of lines with comment.
   * @access public
   * @return int        Count of lines with comment.
   */
  public function getComments() { return $this->comments; }
  /* ------------------------- */

  /* ======================================================== */


  /* ====================== PUBLICS ========================= */


  /*------------ CONSTRUCTOR, DESTRUCTOR -------------*/

  /**
   * Constructor of Compiler object.
   *
   * This method is create when instatiating Compiler object. It opens
   * streams (aka files), check header of input, writes header of output.
   * @access public
   * @param string istream   Name of input file.
   * @param string ostream   Name of output file.
   */
  public function __construct($istream, $ostream)
  {
    // create streams
    $this->in = new FileReader($istream);
    $this->out = new FileWriter($ostream);
    // read header
    if( $this->in->read() != ".IPPcode18") throw new Exception("Missing '.IPPcode18' header");
    // write XML header
    $this->out->write("<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n");
    $this->out->write("<program language=\"IPPcode18\">\n");
  }

  /**
   * Destructor of Compiler object.
   *
   * This method writes tail to output, closes streams. It is called implicitly.
   * @access public
   */
  public function __destruct()
  {
    // write XML tail
    $this->out->write("</program>\n");
    // close streams
    unset($this->out);
    unset($this->int);
  }
  /*------------------------------------------------*/


  /*---------- MAIN FUNCTIONALITY --------------*/

  /**
   * Processes one line of the input.
   *
   * This method reads one line of the input and analyse, if it is valid
   * lexically and syntactically (opcode and arguments). Then it
   * represents the instruction with Instruction object and writes it
   * in XML representation to output. Returns False, if error occurred.
   * @access public
   * @return bool Success.
   */
  public function ProcessLine()
  {

    // read
    if(($str = $this->in->read()) == false)
    {
      if($this->in->eof()) return False; // EOF check
      else return True;
    }


    // generate instruction
    $i = $this->GenerateInstruction($str);
    if($i == NULL) return True;

    // set her order
    $this->loc++;
    $i->setOrder($this->loc);

    // write the instruction to XML
    $this->out->write( $i->toXML() );

    return True;
  }
  /*-------------------------------------------*/

  /*=============================================================*/


  /*======================= GENERATORS ==========================*/

  /**
   * Generates Argument from constant.
   *
   * If input string matches constant (lexically),
   * it is parsed and returned as Argument object.
   * Otherwise NULL is returned.
   * @access private
   * @param string str       String to generate from.
   * @param int num          Order of argument.
   * @return Argument | NULL Representation of $str.
   */
  private function GenerateConstant($str, $num)
  {
    global $const_regex;
    if( preg_match($const_regex, $str) )
    {
      list($type, $data) = explode('@', $str, 2);
      if($type == "string")
      {
        $this->CheckEscaped($data);
        $data = str_replace('<', '&lt;', $data);
        $data = str_replace('>', '&gt;', $data);
        $data = str_replace('&', '&amp;', $data);
        $data = str_replace('\'', '&apos;', $data);
        $data = str_replace('"', '&quot;', $data);
      }

      return new Argument($data, $type, $num);
    }
    else return NULL;
  }

  /**
   * Generates Argument from variable.
   *
   * If input string matches variable (lexically),
   * it is parsed and returned as Argument object.
   * Otherwise NULL is returned.
   * @access private
   * @param string str       String to generate from.
   * @param int num          Order of argument.
   * @return Argument | NULL Representation of $str.
   */
  private function GenerateVariable($str, $num)
  {
    global $var_regex;
    if( preg_match($var_regex, $str) )
    {
      return new Argument($str, "var", $num);
    }
    else return NULL;
  }

  /**
   * Generates Argument from argument.
   *
   * If input string matches argument (variable or constant)
   * (lexically), it is parsed and returned as Argument object.
   * Otherwise NULL is returned.
   * @access private
   * @param string str       String to generate from.
   * @param int num          Order of argument.
   * @return Argument | NULL Representation of $str.
   */
  private function GenerateArgument($str, $num)
  {
    $var = $this->GenerateVariable($str, $num);
    if($var != NULL) return $var;

    $constant = $this->GenerateConstant($str, $num);
    if($constant != NULL) return $constant;

    return NULL;
  }

  /**
   * Generates Argument from label.
   *
   * If input string matches label (lexically),
   * it is parsed and returned as Argument object.
   * Otherwise NULL is returned.
   * @access private
   * @param string str       String to generate from.
   * @param int num          Order of argument.
   * @return Argument | NULL Representation of $str.
   */
  private function GenerateLabel($str, $num)
  {
    global $id_regex;
    if( preg_match($id_regex, $str) )
    {
      return new Argument($str, "label", $num);
    }
    else return NULL;
  }

  /**
   * Generates Argument from type.
   *
   * If input string matches type (lexically),
   * it is parsed and returned as Argument object.
   * Otherwise NULL is returned.
   * @access private
   * @param string str       String to generate from.
   * @param int num          Order of argument.
   * @return Argument | NULL Representation of $str.
   */
  private function GenerateType($str, $num)
  {
    global $type_regex;
    if( preg_match($type_regex, $str) )
    {
      return new Argument($str, "type", $num);
    }
    else return NULL;
  }

  /**
   * Generates Instruction from code line.
   *
   * Recieves rough $line from input. Parses it
   * and returns Instruction object of its
   * representation or NULL, if error occurred.
   * @access private
   * @param string line       String to generate from.
   * @return Instruction | NULL Representation of $line.
   */
  private function GenerateInstruction($line)
  {
    // separate line to list
    $l = $this->SeparateLine($line);
    if($l == NULL) return NULL;

    // 0 arguments ------------------------------
    if(($l[0] == 'PUSHFRAME')
    || ($l[0] == 'CREATEFRAME')
    || ($l[0] == 'POPFRAME')
    || ($l[0] == 'RETURN')
    || ($l[0] == 'BREAK') )
    {
      // args bad count
      if(count($l) != 1) throw new Exception("Argument error in instruction ".$l[0]);
      // sample fill
      $l[1] = NULL;
      $l[2] = NULL;
      $l[3] = NULL;
    }

    // 1 argument --------------------------------
    elseif(($l[0] == 'PUSHS')
        || ($l[0] == 'WRITE')
        || ($l[0] == 'DPRINT') )
    {
      // args bad count
      if(count($l) != 2) throw new Exception("Argument error in instruction ".$l[0]);
      // process args
      $l[1] = $this->GenerateArgument( $l[1], 1 );
      // sample fill
      $l[2] = NULL;
      $l[3] = NULL;
      // args error
      if($l[1] == NULL) throw new Exception("Argument error in instruction ".$l[0]);
    }

    // 1 argument, first is variable --------------
    elseif(($l[0] == 'DEFVAR')
        || ($l[0] == 'POPS') )
    {
      // args bad count
      if(count($l) != 2) throw new Exception("Argument error in instruction".$l[0]);
      // process args
      $l[1] = $this->GenerateVariable( $l[1], 1 );
      // sample fill
      $l[2] = NULL;
      $l[3] = NULL;
      // args error
      if($l[1] == NULL) throw new Exception("Argument error in instruction ".$l[0]);
    }


    // 1 argument, first is label ------------------
    elseif(($l[0] == 'CALL')
        || ($l[0] == 'LABEL')
        || ($l[0] == 'JUMP') )
    {
      // args bad count
      if(count($l) != 2) throw new Exception("Argument error in instruction ".$l[0]);
      // process args
      $l[1] = $this->GenerateLabel( $l[1], 1 );
      // sample fill
      $l[2] = NULL;
      $l[3] = NULL;
      // args error
      if($l[1] == NULL) throw new Exception("Argument error in instruction ".$l[0]);
    }


    // 2 arguments
    elseif(($l[0] == 'MOVE')
        || ($l[0] == 'INT2CHAR')
        || ($l[0] == 'NOT')
        || ($l[0] == 'STRLEN')
        || ($l[0] == 'TYPE') )
    {
      // args bad count
      if(count($l) != 3) throw new Exception("Argument error in instruction ".$l[0]);
      // process args
      $l[1] = $this->GenerateVariable( $l[1], 1 );
      $l[2] = $this->GenerateArgument( $l[2], 2 );
      // sample fill
      $l[3] = NULL;
      // args error
      if(($l[1] == NULL) || ($l[2] == NULL)) throw new Exception("Argument error in instruction ".$l[0]);
    }


    // 2 arguments, second is type
    elseif(($l[0] == 'READ'))
    {
      // args bad count
      if(count($l) != 3) throw new Exception("Argument error in instruction ".$l[0]);
      // process args
      $l[1] = $this->GenerateVariable( $l[1], 1 );
      $l[2] = $this->GenerateType( $l[2], 2 );
      // sample fill
      $l[3] = NULL;
      // args error
      if(($l[1] == NULL) || ($l[2] == NULL)) throw new Exception("Argument error in instruction ".$l[0]);
    }


    // 3 arguments
    elseif(($l[0] == 'ADD')
        || ($l[0] == 'SUB')
        || ($l[0] == 'MUL')
        || ($l[0] == 'IDIV')
        || ($l[0] == 'LT')
        || ($l[0] == 'GT')
        || ($l[0] == 'EQ')
        || ($l[0] == 'AND')
        || ($l[0] == 'OR')
        || ($l[0] == 'STRI2INT')
        || ($l[0] == 'CONCAT')
        || ($l[0] == 'GETCHAR')
        || ($l[0] == 'SETCHAR') )
    {
      // args bad count
      if(count($l) != 4) throw new Exception("Argument error in instruction ".$l[0]);
      // process args
      $l[1] = $this->GenerateVariable( $l[1], 1 );
      $l[2] = $this->GenerateArgument( $l[2], 2 );
      $l[3] = $this->GenerateArgument( $l[3], 3 );
      // args error
      if(($l[1] == NULL) || ($l[2] == NULL) || ($l[3] == NULL)) throw new Exception("Argument error in instruction ".$l[0]);
    }


    // 3 arguments, first is label
    elseif(($l[0] == 'JUMPIFEQ')
        || ($l[0] == 'JUMPIFNEQ') )
    {
      // args bad count
      if(count($l) != 4) throw new Exception("Argument error in instruction ".$l[0]);
      // process args
      $l[1] = $this->GenerateLabel( $l[1], 1 );
      $l[2] = $this->GenerateArgument( $l[2], 2 );
      $l[3] = $this->GenerateArgument( $l[3], 3 );
      // args error
      if(($l[1] == NULL) || ($l[2] == NULL) || ($l[3] == NULL)) throw new Exception("Argument error in instruction ".$l[0]);
    }

    // none of these
    else throw new Exception("Unknown instruction ".$l[0]);

    // return Instruction
    return new Instruction($l[0], $l[1], $l[2], $l[3]);
  }

  /**
   * Sparates the input line.
   *
   * Strips and splits rough line from input into list of strings.
   * @access private
   * @param string line      String to generate from.
   * @return list | NULL Splitted $line.
   */
  private function SeparateLine($line)
  {
    $line = trim($line);

    // uncomment
    if( preg_match('/(.*)#(.*)/', $line) ) {
      $line = preg_split('/#/', $line );
      $line = trim($line[0]);
      $this->comments++;
    }

    if($line == "") return NULL; // skip empty

    $l = preg_split('/\s+/', $line ); // splits line to list
    $l[0] = strtoupper($l[0]);   // capitalize opcode (it is case-insensitive)

    return $l;
  }

  /*=============================================================*/

  private function CheckEscaped($str)
  {
    if( preg_match('/\\\(?![0-9]{3})/', $str) ) {
      throw new Exception("Invalid escape sequence ".$str);
    }
  }

}

?>
