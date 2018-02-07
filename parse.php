<?php

include 'analyze.php';
include 'io.php';

$In = new FileReader();
$Out = new FileWriter();

function PrintInstruction($i)
{
  global $Out;

  static $cnt = 1;
  $i->setOrder($cnt);
  $Out->write( $i->toXML() );
  $cnt++;
}

$cnt = 1;
while( ($str = $In->read()) != false)
{
  if($In->eof()) { break; }

  // parses line
  if( ($i = GenerateInstruction($str)) == NULL)
  {
    echo "ERROR!";
    break;
  }

  PrintInstruction($i);

}

?>
