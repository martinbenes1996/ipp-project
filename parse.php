<?php

include 'analyze.php';
include 'io.php';

$In = new FileReader();
$Out = new FileWriter();
$Err = new FileWriter('php://stderr');

function PrintInstruction($i)
{
  global $Out;
  global $Xml;
  static $cnt = 1;

  $i->setOrder($cnt);
  $Out->write( $i->toXML() );
  $cnt++;
}


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
