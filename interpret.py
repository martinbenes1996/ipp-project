#!/usr/bin/env python3

"""
Interpret module.

This module is main to call when interpreting XML IPPcode18.

Package: interpret.py
Author: xbenes49
Copyright: Martin Benes (c) 2018
"""

import sys # exit

import int_error as Err # exceptions
import int_processor as Proc

def main():
    """ Main function. """
    try:

        # create Processor
        p = Proc.Processor(sys.argv)

    # parameters
    except Err.ParameterException as e:
        print(e)
        exit(e.GetCode())

    # --help
    except Err.HelpException() as e:
        sys.exit(e.GetCode())



    # ============================================ #
    try:

        # run
        while(p.NextInstruction()):
            pass

    # error
    except Exception as e:
        print(e) # print to stderr
        sys.exit(e.GetCode())

    # ============================================ #

    # prints statistics about run
    p.PrintStatistics()




# calling main
if __name__=="__main__":
    main()
