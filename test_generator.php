<?php

class HTMLGenerator
{
  private $tests = array();
  private $it = 1;

  public function AddTest($test)
  {
    $this->tests[$this->it] = $test;
    $this->it = $this->it + 1;
  }

  public function Generate()
  {
    $o = new FileWriter();

    $o->write("<!DOCTYPE html>\n<html lang=\"en\">\n\t<head>\n");
    $o->write("\t\t<meta charset=\"UTF-8\">\n\t\t<title>Test report | IPP project</title>\n");
    $o->write("\t</head>\n\t<body>\n");
    $o->write("\t\t<header style=\"text-align: center\">\n");
    $o->write("\t\t\t<h1>Testing report</h1>\n");
    $o->write("\t\t</header>\n\t\t<hr>\n\n");

    $o->write("\t\t<!-- Test results -->\n\t\t<main>\n");
    $o->write("\t\t\t<table style=\"width:100%; text-align: center\">\n");
    $o->write("\t\t\t\t<tr>\n");
    $o->write("\t\t\t\t\t<th>No.</th>\n");
    $o->write("\t\t\t\t\t<th>File</th>\n");
    $o->write("\t\t\t\t\t<th>Parse code</th>\n");
    $o->write("\t\t\t\t\t<th>Interpret code</th>\n");
    $o->write("\t\t\t\t\t<th>Error message</th>\n");
    $o->write("\t\t\t\t\t<th>Status</th>\n");
    $o->write("\t\t\t\t</tr>\n\n");

    foreach($this->tests as $it => $t)
    {
      $o->write("\t\t\t\t<!-- Test ".$it.". -->\n");
      $o->write("\t\t\t\t<tr>\n");
      $o->write("\t\t\t\t\t<td>".$it."</td>\n");
      $o->write("\t\t\t\t\t<td>".$t->GetTestName()."</td>\n");
      $o->write("\t\t\t\t\t<td>".$t->GetParseCode()."</td>\n");
      $o->write("\t\t\t\t\t<td>".$t->GetIntCode()."</td>\n");
      $o->write("\t\t\t\t\t<td>".$t->GetErrorMessage()."</td>\n");
      if($t->GetStatus()) { $o->write("\t\t\t\t\t<td>OK</td>\n"); }
      else { $o->write("\t\t\t\t\t<td>FAIL</td>\n"); }

      $o->write("\t\t\t\t</tr>\n");
    }

    $o->write("\t\t\t</table>\n");
    $o->write("\t\t</main>\n");
    $o->write("\t\t<hr>\n");
    $o->write("\t</body>\n");
    $o->write("</html>\n");
  }
}

?>
