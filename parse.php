<?php

include 'analyze.php';

// streams
$ErrOut = new FileWriter('php://stderr');  // stderr

// ==================================================
$c = new Compiler(); // create compiler

if( $c->isBroken() ) {    // error
  $ErrOut->write( $c->getErrorMessage() );
  exit( $c->getErrorCode() );
}

while( $c->ProcessLine() ) { } // read cycle

// ==================================================

$ErrOut->write( $c->getErrorMessage() ); // read to stderr
exit( $c->getErrorCode() ); // end

?>
