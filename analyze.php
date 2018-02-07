<?php

class Argument
{
  function __construct($data = "", $type = "")
  {
    $this->data = $data;
    $this->type = $type;
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

function GenerateInstruction($line)
{
  $l = split(' ', trim( $line )); // splits to list
  $l[0] = strtoupper($l[0]);

  if( $l[0] == 'MOVE' ) {

  } elseif( $l[0] == 'CREATEFRAME' ) {

  } elseif( $l[0] == 'PUSHFRAME' ) {

  } elseif( $l[0] == 'POPFRAME' ) {

  } elseif( $l[0] == 'DEFVAR' ) {

  } elseif( $l[0] == 'CALL' ) {

  } elseif( $l[0] == 'RETURN' ) {

  } elseif( $l[0] == 'PUSHS' ) {

  } elseif( $l[0] == 'POPS' ) {

  } elseif( $l[0] == 'ADD' ) {

  } elseif( $l[0] == 'SUB' ) {

  } elseif( $l[0] == 'MUL' ) {

  } elseif( $l[0] == 'IDIV' ) {

  } elseif( $l[0] == 'LT' ) {

  } elseif( $l[0] == 'GT' ) {

  } elseif( $l[0] == 'EQ' ) {

  } elseif( $l[0] == 'AND' ) {

  } elseif( $l[0] == 'OR' ) {

  } elseif( $l[0] == 'NOT' ) {

  } elseif( $l[0] == 'INT2CHAR' ) {

  } elseif( $l[0] == 'STRI2INT' ) {

  } elseif( $l[0] == 'READ' ) {

  } elseif( $l[0] == 'WRITE' ) {

  } elseif( $l[0] == 'CONCAT' ) {

  } elseif( $l[0] == 'STRLEN' ) {

  } elseif( $l[0] == 'GETCHAR' ) {

  } elseif( $l[0] == 'SETCHAR' ) {

  } elseif( $l[0] == 'TYPE' ) {

  } elseif( $l[0] == 'LABEL' ) {

  } elseif( $l[0] == 'JUMP' ) {

  } elseif( $l[0] == 'JUMPIFEQ' ) {

  } elseif( $l[0] == 'JUMPIGNEQ' ) {

  } elseif( $l[0] == 'DPRINT' ) {

  } elseif( $l[0] == 'BREAK' ) {

  } else {
    return NULL;
  }

  $a = new Argument(42, "int");
  $a->setNum(1);
  return new Instruction(42, $a, NULL, NULL);
}

?>
