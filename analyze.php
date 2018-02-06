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
      ("<arg".$this->num." type=".$this->type.">" .
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
    return "<instruction order=" . $this->order .
                      " opcode=" . $this->opcode .">\n".
        (($this->arg1 != NULL) ? "\t".$this->arg1->toXML() : "") .
        (($this->arg2 != NULL) ? "\t".$this->arg2->toXML() : "") .
        (($this->arg3 != NULL) ? "\t".$this->arg3->toXML() : "") .
      "</instruction>\n";

  }
}

function ParseLine($line)
{
  $a = new Argument(42, "int");
  $a->setNum(1);
  return new Instruction(42, $a, NULL, NULL);
}

?>
