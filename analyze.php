<?php

class Argument
{
  function __construct($data, $type, $num)
  {

    $this->data = $data;
    $this->type = $type;
    $this->num = $num;
  }

  function setNum($num)
  {
    $this->num = $num;
  }

  function toXML()
  {
    return ( $this->type != "" ) ?
      ("<arg".$this->num." type=\"".$this->type."\">" .
          $this->data .
       "</arg".$this->num.">\n") : "";
  }
}

class Instruction
{
  function __construct($opcode, $arg1 = NULL, $arg2 = NULL, $arg3 = NULL)
  {
      $this->opcode = $opcode;
      $this->arg1 = $arg1;
      $this->arg2 = $arg2;
      $this->arg3 = $arg3;
  }

  function setOrder($order)
  {
    $this->order = $order;
  }

  function toXML()
  {
    return "<instruction order=\"" . $this->order . "\" ".
                       "opcode=\"" . $this->opcode ."\" >\n".
        (($this->arg1) ? "\t".$this->arg1->toXML() : "") .
        (($this->arg2) ? "\t".$this->arg2->toXML() : "") .
        (($this->arg3) ? "\t".$this->arg3->toXML() : "") .
      "</instruction>\n";

  }
}

function GenerateArgument($str, $num)
{
  return new Argument("3", "int", $num);
}

function GenerateLabel($str, $num)
{
  return new Argument($str, "label", $num);
}

function GenerateType($str, $num)
{
  return new Argument($str, "label", $num);
}

function GenerateInstruction($line)
{
  $l = split(' ', trim( $line )); // splits to list
  $l[0] = strtoupper($l[0]);

  // 0 arguments ------------------------------
  if(($l[0] == 'PUSHFRAME')
  || ($l[0] == 'CREATEFRAME')
  || ($l[0] == 'POPFRAME')
  || ($l[0] == 'RETURN')
  || ($l[0] == 'BREAK') )
  {
    // args bad count
    if( count($l) != 1 ) { return NULL; }

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
    if( count($l) != 2 ) { return NULL; }
    // process args
    $l[1] = GenerateArgument( $l[1], 1 );
    // sample fill
    $l[2] = NULL;
    $l[3] = NULL;
    // args error
    if($l[1] == NULL) { return NULL; }
  }


  // 1 argument, first is label ------------------
  elseif(($l[0] == 'CALL')
      || ($l[0] == 'LABEL')
      || ($l[0] == 'JUMP') )
  {
    // args bad count
    if( count($l) != 2 ) { return NULL; }
    // process args
    $l[1] = GenerateLabel( $l[1], 1 );
    // sample fill
    $l[2] = NULL;
    $l[3] = NULL;
    // args error
    if($l[1] == NULL) { return NULL; }
  }


  // 2 arguments
  elseif(($l[0] == 'MOVE')
      || ($l[0] == 'INT2CHAR')
      || ($l[0] == 'NOT')
      || ($l[0] == 'STRLEN')
      || ($l[0] == 'TYPE') )
  {
    // args bad count
    if( count($l) != 3 ) { return NULL; }
    // process args
    $l[1] = GenerateArgument( $l[1], 1 );
    $l[2] = GenerateArgument( $l[2], 2 );
    // sample fill
    $l[3] = NULL;
    // args error
    if(($l[1] == NULL) || ($l[2] == NULL)) { return NULL; }
  }


  // 2 arguments, second is type
  elseif(($l[0] == 'READ'))
  {
    // args bad count
    if( count($l) != 3 ) { return NULL; }
    // process args
    $l[1] = GenerateArgument( $l[1], 1 );
    $l[2] = GenerateType( $l[2], 2 );
    // sample fill
    $l[3] = NULL;
    // args error
    if(($l[1] == NULL) || ($l[2] == NULL)) { return NULL; }
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
    if( count($l) != 4 ) { return NULL; }
    // process args
    $l[1] = GenerateArgument( $l[1], 1 );
    $l[2] = GenerateArgument( $l[2], 2 );
    $l[3] = GenerateArgument( $l[3], 3 );
    // args error
    if(($l[1] == NULL) || ($l[2] == NULL) || ($l[3] == NULL)) { return NULL; }
  }


  // 3 arguments, first is label
  elseif(($l[0] == 'JUMPIGEQ')
      || ($l[0] == 'JUMPIFNEQ') )
  {
    // args bad count
    if( count($l) != 4 ) { return NULL; }
    // process args
    $l[1] = GenerateLabel( $l[1], 1 );
    $l[2] = GenerateArgument( $l[2], 2 );
    $l[3] = GenerateArgument( $l[3], 3 );
    // args error
    if(($l[1] == NULL) || ($l[2] == NULL) || ($l[3] == NULL)) { return NULL; }
  }

  // none of these
  else
  {
    return NULL;
  }

  return new Instruction($l[0], $l[1], $l[2], $l[3]);
}

?>
