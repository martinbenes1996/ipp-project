
"""
Processor module.

This module implements the core of interpret.

Package: processor.py
Author: xbenes49
Copyright: Martin Benes (c) 2018
"""

# system
import re # Regex
# user
import read as Read # Reader
import write as Write # Writer

class HelpException(Exception):
    def __init__(self):
        pass
    def __str__(self):
        return ""

class Processor:
    """ This is Processor class. """

    def __init__(self, argv):
        """ Constructor of Processor. Initializes the parts of system. """
        self.ProcessArguments(argv)

        # create writer
        self.writer = Write.Writer(argv)
        # create reader
        self.reader = Read.Reader(self.src)

    def NextInstruction():
        """ Runs next instruction. """
        try:
            instruction = self.reader.GetInstruction(self.ic)
            self.ic = instruction.Execute()
        except Read.ProgramExitException:
            return False
        

    def ProcessArguments(self, argv):
        """ Processes arguments from terminal. """
        # default
        self.src = "sys.stdin"
        self.ic = 0

        # no arguments
        if len(argv) is 1:
            return

        # go through the argument list
        src_regex = re.compile(r'^((?<=--source=\")[^/\"~]+(?=\"))|((?<=--source=)[^/\"~]+(?=))$')
        for a in argv[1:]:
            # help
            if a is "--help":
                self.PrintHelp()
                raise HelpException()

            # source
            elif src_regex.search(a) is not None:
                self.src = src_regex.search(a).group()

            else:
                raise Exception("Unknown parameter " + a + "!")

    def PrintHelp(self):
        print("Printing help!")

    def PrintStatistics(self):
        pass
