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
    return "\t<instruction order=\"" . $this->order . "\" ".
                         "opcode=\"" . $this->opcode ."\" >\n".
        (($this->arg1) ? "\t\t".$this->arg1->toXML() : "") .
        (($this->arg2) ? "\t\t".$this->arg2->toXML() : "") .
        (($this->arg3) ? "\t\t".$this->arg3->toXML() : "") .
      "\t</instruction>\n";

  }
}

$const_regex = '/(int@[-+]{0,1}[0-9][0-9]*)|(string@[^\s\b#]*)|(bool@((true)|(false)))/';
$id_regex = '/[-a-zA-Z_$&%*]+/';
$var_regex = '/((LF)|(GF)|(TF))@[-a-zA-Z_$&%*]+/';


?>
