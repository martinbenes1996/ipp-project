<?php

include 'typedefs.php';
include 'functions.php';

class Compiler
{
  function __construct()
  {
    $this->in = new FileReader();  // stdin
    $this->out = new FileWriter(); // stdout

    $this->works = True;
    $this->eof = False;
    $this->err_msg = "";
    $this->err_code = 0;

    if( $this->in->read() != ".IPPcode18") // reads header
    {
      $this->works = False;
      $this->err_msg = "Missing '.IPPcode18' header.";
    }

    $this->out->write("<?xml version=\"1.0\"?>\n"); // writes
    $this->out->write("<program language=\"IPPcode18\">\n");

  }

  function __destruct()
  {
    $this->out->write("</program>\n");
  }

  function isBroken() { return ! $this->works; }
  function getErrorMessage() { return $this->err_msg; }
  function getErrorCode() { return $this->err_code; }

  function GenerateConstant($str, $num)
  {
    global $const_regex;
    if( preg_match($const_regex, $str) )
    {
      list($type, $data) = explode('@', $str, 2);
      return new Argument($data, $type, $num);
    }
    else return NULL;
  }

  function GenerateVariable($str, $num)
  {
    global $var_regex;
    if( preg_match($var_regex, $str) )
    {
      return new Argument($str, "var", $num);
    }
    else return NULL;
  }

  function GenerateArgument($str, $num)
  {
    $var = $this->GenerateVariable($str, $num);
    if($var != NULL) return $var;

    $constant = $this->GenerateConstant($str, $num);
    if($constant != NULL) return $constant;

    return NULL;
  }

  function GenerateLabel($str, $num)
  {
    global $id_regex;
    if( preg_match($id_regex, $str) )
    {
      return new Argument($str, "label", $num);
    }
    else return NULL;
  }

  function GenerateType($str, $num)
  {
    global $type_regex;
    if( preg_match($type_regex, $str) )
    {
      return new Argument($str, "type", $num);
    }
    else return NULL;
  }

  function GenerateInstruction($line)
  {
    // separate line to list
    $l = SeparateLine($line);
    if($l == NULL) return NULL;

    // 0 arguments ------------------------------
    if(($l[0] == 'PUSHFRAME')
    || ($l[0] == 'CREATEFRAME')
    || ($l[0] == 'POPFRAME')
    || ($l[0] == 'RETURN')
    || ($l[0] == 'BREAK') )
    {
      // args bad count
      if(count($l) != 1)
      {
        $this->err_msg = "Bad arguments in instruction " . $l[0];
        $this->err_code = 1;
        $this->works = False;
        return NULL;
      }

      // sample fill
      $l[1] = NULL;
      $l[2] = NULL;
      $l[3] = NULL;
    }

    // 1 argument --------------------------------
    elseif(($l[0] == 'DEFVAR')
        || ($l[0] == 'PUSHS')
        || ($l[0] == 'POPS')
        || ($l[0] == 'WRITE')
        || ($l[0] == 'JUMP')
        || ($l[0] == 'DPRINT') )
    {
      // args bad count
      if(count($l) != 2)
      {
        $this->err_msg = "Bad arguments in instruction " . $l[0];
        $this->err_code = 1;
        $this->works = False;
        return NULL;
      }

      // process args
      $l[1] = $this->GenerateArgument( $l[1], 1 );
      // sample fill
      $l[2] = NULL;
      $l[3] = NULL;

      // args error
      if($l[1] == NULL)
      {
        $this->err_msg = "First argument error in instruction " . $l[0];
        $this->err_code = 1;
        $this->works = False;
        return NULL;
      }
    }


    // 1 argument, first is label ------------------
    elseif(($l[0] == 'CALL')
        || ($l[0] == 'LABEL')
        || ($l[0] == 'JUMP') )
    {
      // args bad count
      if(count($l) != 2)
      {
        $this->err_msg = "Bad arguments in instruction " . $l[0];
        $this->err_code = 1;
        $this->works = False;
        return NULL;
      }

      // process args
      $l[1] = $this->GenerateLabel( $l[1], 1 );
      // sample fill
      $l[2] = NULL;
      $l[3] = NULL;

      // args error
      if($l[1] == NULL)
      {
        $this->err_msg = "First argument error in instruction " . $l[0];
        $this->err_code = 1;
        $this->works = False;
        return NULL;
      }
    }


    // 2 arguments
    elseif(($l[0] == 'MOVE')
        || ($l[0] == 'INT2CHAR')
        || ($l[0] == 'NOT')
        || ($l[0] == 'STRLEN')
        || ($l[0] == 'TYPE') )
    {
      // args bad count
      if(count($l) != 3)
      {
        $this->err_msg = "Bad arguments in instruction " . $l[0];
        $this->err_code = 1;
        $this->works = False;
        return NULL;
      }

      // process args
      $l[1] = $this->GenerateArgument( $l[1], 1 );
      $l[2] = $this->GenerateArgument( $l[2], 2 );
      // sample fill
      $l[3] = NULL;

      // args error
      if(($l[1] == NULL) || ($l[2] == NULL))
      {
        $this->err_msg = "Argument error in instruction " . $l[0];
        $this->err_code = 1;
        $this->works = False;
        return NULL;
      }
    }


    // 2 arguments, second is type
    elseif(($l[0] == 'READ'))
    {
      // args bad count
      if(count($l) != 3)
      {
        $this->err_msg = "Bad arguments in instruction " . $l[0];
        $this->err_code = 1;
        $this->works = False;
        return NULL;
      }

      // process args
      $l[1] = $this->GenerateArgument( $l[1], 1 );
      $l[2] = $this->GenerateType( $l[2], 2 );
      // sample fill
      $l[3] = NULL;

      // args error
      if(($l[1] == NULL) || ($l[2] == NULL))
      {
        $this->err_msg = "Argument error in instruction " . $l[0];
        $this->err_code = 1;
        $this->works = False;
        return NULL;
      }
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
      if(count($l) != 4)
      {
        $this->err_msg = "Bad arguments in instruction " . $l[0];
        $this->err_code = 1;
        $this->works = False;
        return NULL;
      }

      // process args
      $l[1] = $this->GenerateArgument( $l[1], 1 );
      $l[2] = $this->GenerateArgument( $l[2], 2 );
      $l[3] = $this->GenerateArgument( $l[3], 3 );

      // args error
      if(($l[1] == NULL) || ($l[2] == NULL) || ($l[3] == NULL))
      {
        $this->err_msg = "Argument error in instruction " . $l[0];
        $this->err_code = 1;
        $this->works = False;
        return NULL;
      }
    }


    // 3 arguments, first is label
    elseif(($l[0] == 'JUMPIFEQ')
        || ($l[0] == 'JUMPIFNEQ') )
    {
      // args bad count
      if(count($l) != 4)
      {
        $this->err_msg = "Bad arguments in instruction " . $l[0];
        $this->err_code = 1;
        $this->works = False;
        return NULL;
      }

      // process args
      $l[1] = $this->GenerateLabel( $l[1], 1 );
      $l[2] = $this->GenerateArgument( $l[2], 2 );
      $l[3] = $this->GenerateArgument( $l[3], 3 );

      // args error
      if(($l[1] == NULL) || ($l[2] == NULL) || ($l[3] == NULL))
      {
        $this->err_msg = "Argument error in instruction " . $l[0];
        $this->err_code = 1;
        $this->works = False;
        return NULL;
      }
    }

    // none of these
    else
    {
      $this->err_msg = "Unknown instruction " . $l[0];
      $this->err_code = 1;
      $this->works = False;
      return NULL;
    }

    return new Instruction($l[0], $l[1], $l[2], $l[3]);
  }

  function ProcessLine()
  {
    static $cnt = 1;

    if( (($str = $this->in->read()) == false) || $this->in->eof()) return False; // EOF check
    $i = $this->GenerateInstruction($str); // generate instruction

    if($i == NULL) return $this->works;

    $i->setOrder($cnt);
    $cnt++;

    $this->out->write( $i->toXML() );

    return True;
  }


}

?>
