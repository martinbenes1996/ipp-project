<?php

  class File
  {
    public $handle = null;
    public function __construct($name, $type) { $this->handle = fopen($name, $type); }
    function __destruct() { fclose($this->handle); }
    function eof() { return feof($this->handle); }
  }

  class FileReader extends File
  {
    function __construct($name = 'php://stdin')
    {
      parent::__construct($name, 'r');
    }

    function read() { return fgets($this->handle); }
  }

  class FileWriter extends File
  {
    function __construct($name = 'php://stdout')
    {
      parent::__construct($name, 'w');
    }

    function write($str) { return fputs($this->handle, $str); }
  }

?>
