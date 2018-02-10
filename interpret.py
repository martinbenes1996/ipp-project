#!/usr/bin/env python3

"""
Interpret module.

This module is main to call when interpreting XML IPPcode18.

Package: interpret.py
Author: xbenes49
Copyright: Martin Benes (c) 2018
"""

import processor as Proc
import sys # exit

def main():
    """ Main function. """
    try:

        # create Processor
        p = Proc.Processor(sys.argv)

    # --help
    except Proc.HelpException():
        sys.exit(0)
    # error
    except Exception:
        sys.exit(1)


    # ============================================ #
    try:

        # run
        while(p.NextInstruction()):
            pass

    # error
    except:
        sys.exit(1)

    # ============================================ #

    # prints statistics about run
    p.PrintStatistics()




# calling main
if __name__=="__main__":
    main()
