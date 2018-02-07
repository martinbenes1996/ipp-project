<?php

include 'analyze.php';
include 'io.php';

// streams
$In = new FileReader();                 // stdin
$Out = new FileWriter();                // stdout
$Err = new FileWriter('php://stderr');  // stderr

function InitializeXML()
{
  global $In;
  global $Out;

  $Out->write("<?xml version=\"1.0\"?>\n");
  $Out->write("<program language=\"IPPcode18\">\n");

  if( $In->read() != ".IPPcode18")
  {
    echo "ERROR!";
    return NULL;
  }

  return True;
}

function FinalizeXML()
{
  global $Out;
  $Out->write("</program>\n");
}

function PrintInstruction($i)
{
  global $Out;
  static $cnt = 1; // global instruction counter

  $i->setOrder($cnt);
  $Out->write( $i->toXML() );
  $cnt++;
}

// ==================================================
// init xml
if( InitializeXML() == NULL ) { exit(1); }


// read cycle ----------------------------
while( ($str = $In->read()) != false)
{
  if($In->eof()) { break; } // EOF check


  // parse line
  $i = GenerateInstruction($str);


  // event
  if( $i == NULL )
  {

    if( $Skip == True ) // empty line
    {
      echo "skip\n";
      $Skip = False;
      continue;
    }

    else // error
    {
      echo "ERROR!";
      break;
    }
  }

  PrintInstruction($i); // export to XML

}
//----------------------------------------

// end XML
FinalizeXML();
// ==================================================

?>
