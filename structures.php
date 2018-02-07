<?php

  class Stack
  {
    function __construct()
    {
      $this->count = 0;
      $this->a = array();
    }

    function push($it)
    {
      $this->a[$this->count] = $it;
      $this->count++;
    }

    function top_pop()
    {
      if($this->count > 0)
      {
        $this->count--;
        $val = $this->a[$this->count];
        unset($this->a[$this->count]);
        return $val;
      }
    }

    function pop()
    {
      if($this->count > 0)
      {
        $this->count--;
        unset($this->a[$this->count]);
      }
    }


    function top()
    {
      if($this->count > 0)
      {
        return $this->a[$this->count - 1];
      }
      else
      {
          return NULL;
      }

    }

  }
