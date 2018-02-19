#!/usr/bin/env php
<?php

/**
 * Test main module.
 *
 * This module is main to call in test. It tests parse.php and interpret.py.
 *
 * @package test.php
 * @author xbenes49
 * @copyright Martin Benes (c) 2018
 */

include 'test_model.php'; // TestConfiguration, FileWriter

/**
 * Error stream.
 * @var FileWriter
 */
$ErrOut = new FileWriter('php://stderr');
$g = new HTMLGenerator();

/* -------- ARGS --------- */
try {

  /**
   * Configuration
   * @var Configuration
   */
  $conf = new TestConfiguration($argv);

// --help occured
} catch(HelpException $e) {
  exit(0);

// bad arguments
} catch(Exception $e) {
  $ErrOut->write( $e->getMessage()."!\n");
  exit(10);
}
/* ----------------------- */

/* -------- RUN --------- */
try {
  // create program
  $program = new Program($conf->GetParse(), $conf->GetInterpret());
  // get set of tests
  $testset = new TestSet( $conf->GetTests() );
  // launch testing
  $results = $testset->Launch( $program );

// error
} catch(Exception $e) {
  $ErrOut->write( $e->getMessage()."!\n");
  exit(10);
}


$results->Generate();

// ==================================================
