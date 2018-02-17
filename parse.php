#!/usr/bin/env php
<?php

/**
 * Parse main module.
 *
 * This module is main to call in this program. It does lexical and syntactic
 * analysis of IPPcode18 and its transcription to XML.
 *
 * @package parse.php
 * @author xbenes49
 * @copyright Martin Benes (c) 2018
 */

include 'parse_compiler.php'; // Compiler, FileWriter

/**
 * Error stream.
 * @var FileWriter
 */
$ErrOut = new FileWriter('php://stderr');


/* -------- ARGS --------- */
try {

  /**
   * Configuration
   * @var Configuration
   */
  $conf = new Configuration($argv);

// --help occured
} catch(HelpException $e) {
  exit(0);

// bad arguments
} catch(Exception $e) {
  $ErrOut->write( $e->getMessage()."!\n");
  exit(10);
}
/* ----------------------- */

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


// print statistics
$conf->PrintStatistics($c->getLOC(), $c->getComments());


?>
