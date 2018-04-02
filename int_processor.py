
"""
Processor module.

This module implements the core of interpret.

Package: int_processor.py
Author: xbenes49
Copyright: Martin Benes (c) 2018
"""

# system
import re # Regex
# user
import int_read as Read # Reader
import int_error as Err
import int_model as Model

class Processor:
    """ This is Processor class. """

    def __init__(self, argv):
        """ Constructor of Processor. Initializes the parts of system. """
        self.ProcessArguments(argv)

        # create reader
        self.reader = Read.Reader(self.src)

    def NextInstruction(self):
        """ Runs next instruction. """
        try:
            instruction = self.reader.Decode()
            try:
                instruction()
            except Err.MyException as e:
                raise e.__class__("Instruction " + str(self.reader.GetPC()) + ": " + str(e))
        except Err.ProgramExitException:
            return False

        return True


    def ProcessArguments(self, argv):
        """ Processes arguments from terminal. """
        # default
        self.src = None
        self.ic = 0

        self.stats = [None]

        # go through the argument list
        src_regex = re.compile(r'((?<=^--source=\")[^="]+(?=\"))|((?<=^--source=)[^="]+(?=))$')
        stats_regex = re.compile(r'(?<=^--stats=)[^="]+$')
        for a in argv[1:]:

            # help
            if a == "--help":
                self.PrintHelp()
                raise Err.HelpException()

            # source
            elif src_regex.search(a) is not None:
                if self.src is not None: raise Err.ParameterException("Multiple occurence of --source=file parameter.")
                self.src = src_regex.search(a).group()

            # stats
            elif stats_regex.search(a) is not None:
                if self.stats[0] is not None: raise Err.ParameterException("Multiple occurence of --stats=file parameter.")
                self.stats[0] = stats_regex.search(a).group()
            
            # instruction count
            elif a == "--insts":
                if "insts" in self.stats: raise Err.ParameterException("Multiple occurence of --insts parameter.")
                self.stats.append("insts")
            
            # variable count
            elif a == "--vars":
                if "vars" in self.stats: raise Err.ParameterException("Multiple occurence of --vars parameter.")
                self.stats.append("vars")


            # error
            else:
                raise Err.ParameterException("Unknown parameter " + a + "!")

        if self.src is None:
            raise Err.ParameterException('--source=file parameter missing.')
        if len(self.stats) > 1 and self.stats[0] is None:
            raise Err.ParameterException('--stats=file parameter missing.')
        if self.stats[0] is not None and len(self.stats) is 1:
            raise Err.ParameterException('--insts or --vars parameter missing.')

    def PrintHelp(self):
        print("Printing help!")

    def PrintStatistics(self):
        insts = Read.run.getInsts()
        varcount = Read.run.getVarCount()
        if self.stats[0] is not None:
            with open(self.stats[0], 'w') as f:
                for p in self.stats[1:]:
                    if p == 'vars':
                        print(varcount, file=f)
                    if p == 'insts':
                        print(insts, file=f)
            
