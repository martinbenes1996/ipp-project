<?php

/**
 * Parse main module.
 *
 * This module is main to call in this programme. It does lexical and syntactic
 * analysis of IPPcode18 and its transcription to XML.
 *
 * @package parse.php
 * @author xbenes49
 * @copyright Martin Benes (c) 2018
 */

include 'analyse.php'; // Compiler, FileWriter

/**
 * Error stream.
 * @var FileWriter
 */
$ErrOut = new FileWriter('php://stderr');


/* ------ ARGS ------ */
foreach( array_slice($argv,1) as $a )
{
  // help
  if($a == "--help")
  {
    PrintHelp();
    exit(0);
  }
  // unknown argument
  else
  {
    $ErrOut->write( "Unknown argument ".$a."!\n" );
    exit(10);
  }
}
/* ----------------- */


// ==================================================

try {
  // create compiler
  $c = new Compiler('php://stdin', 'php://stdout'); // reads from stdin, writes to stdout

  // read cycle
  while( $c->ProcessLine() ) {}

// error occurred
} catch(Exception $e) {
  $ErrOut->write( $e->getMessage()."!\n"); // write to stderr
  exit(21); // end
}

// ==================================================




?>
