
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
import error as Err
import model as Model

class Processor:
    """ This is Processor class. """

    def __init__(self, argv):
        """ Constructor of Processor. Initializes the parts of system. """
        self.ProcessArguments(argv)

        # create writer
        self.writer = Write.Writer(argv)
        # create reader
        self.reader = Read.Reader(self.src)
        Model.PrintModel()

    def NextInstruction(self):
        """ Runs next instruction. """
        try:
            instruction = self.reader.Decode()
            instruction()
        except Err.ProgramExitException:
            print("=== regular exit ===")
            return False

        return True


    def ProcessArguments(self, argv):
        """ Processes arguments from terminal. """
        # default
        self.src = None
        self.ic = 0

        # go through the argument list
        src_regex = re.compile(r'^((?<=--source=\")[^/\"~]+(?=\"))|((?<=--source=)[^/\"~]+(?=))$')
        for a in argv[1:]:
            # help
            if a is "--help":
                self.PrintHelp()
                raise Err.HelpException()

            # source
            elif src_regex.search(a) is not None:
                self.src = src_regex.search(a).group()

            else:
                raise Err.ParameterException("Unknown parameter " + a + "!")

        if self.src is None:
            print("Error")
            raise Err.ParameterException('--source=file parameter missing.')

    def PrintHelp(self):
        print("Printing help!")

    def PrintStatistics(self):
        pass