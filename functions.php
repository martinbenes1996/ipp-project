<?php


$Skip = False; /*< Whether to skip line and continue with another. */

/**
 * @brief   Separates given string (line of IPPcode17 code) to list of strings,
 *          clears it from comments and capitilizes the opcode.
 */
function SeparateLine($line)
{
  global $Skip;
  $line = trim($line);

  // uncomment
  if( preg_match('/(.*)#(.*)/', $line) ) {
    $line = preg_split('/#/', $line );
    $line = trim($line[0]);
  }

  // skip empty
  if($line == "")
  {
    $Skip = True;
    return NULL;
  }

  $l = preg_split('/ /', $line ); // splits line to list
  $l[0] = strtoupper($l[0]);   // capitalize opcode (it is case-insensitive)

  return $l;
}

?>
