<?php

class Test
{
  /* ---------- DATA --------- */
  /**
   * Test filename.
   * @var string
   * @access private
   */
  private $name = null;
  /**
   * Program object.
   * @var Program
   * @access private
   */
  private $program = null;
  /* ------------------------- */

  public function __construct($name, $program)
  {
    $this->name = $name;
    $this->program = $program;
  }

  public function RunTest()
  {
    # test
    $r = $this->program->RunTest($this->name);

    #
  }
}

class HTMLGenerator
{
  private $tests = array();
  private $it = 1;

  public function AddTest($test)
  {
    $this->tests[$this->it] = $test;
    $this->it = $this->it + 1;
  }

  public function GenerateTestReport()
  {
    $o = new FileWriter();

    $o->write("<!DOCTYPE html>\n<html lang=\"en\">\n\t<head>\n");
    $o->write("\t\t<meta charset=\"UTF-8\">\n\t\t<title>Test report | IPP project</title>");
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
    $o->write("\t\t\t\t</tr>\n\n");

    foreach($this->tests as $it => $t)
    {
      $o->write("\t\t\t\t<!-- Test ".$it.". -->\n");
      $o->write("\t\t\t\t<tr>\n");
      $o->write("\t\t\t\t\t<td>".$it."</td>\n");
      $o->write("\t\t\t\t\t<td>".$t->GetTestName()."</td>\n");
      $o->write("\t\t\t\t\t<td>".$t->GetParseCode()."</td>\n");
      if($t->GetParseCode() != 0)
      {
        $o->write("\t\t\t\t\t<td>-</td>\n");
        $o->write("\t\t\t\t\t<td>".$t->GetParseErr()."</td>\n");
      }
      else
      {
        $o->write("\t\t\t\t\t<td>".$t->GetIntCode()."</td>\n");
        if($t->GetIntCode() != 0) { $o->write("\t\t\t\t\t<td>".$t->GetIntErr()."</td>\n"); }
        else { $o->write("\t\t\t\t\t<td>-</td>\n\n"); }
      }
      $o->write("\t\t\t\t</tr>\n");
    }

    $o->write("\t\t\t</table>\n");
    $o->write("\t\t</main>\n");
    $o->write("\t</body>\n");
    $o->write("</html>\n");
  }
}

?>
