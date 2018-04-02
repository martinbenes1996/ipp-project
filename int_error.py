
"""
Error module.

This module includes Exception classes.

Package: int_error.py
Author: xbenes49
Copyright: Martin Benes (c) 2018
"""


class NonErrorException(Exception):
    """ Indicator exception. """
    def __init__(self, errcode):
        """ NonErrorException constructor. """
        self.errcode = errcode
    def __str__(self):
        """ Message getter. """
        return "This should be caught and ignored."
    def GetCode(self):
        """ Code getter. """
        return self.errcode

class HelpException(NonErrorException):
    """ Help indicator. """
    def __init__(self):
        """ HelpException constructor. """
        super().__init__(0)
    def __str__(self):
        """ Message getter. """
        return ""

class ProgramExitException(NonErrorException):
    """ End of program indicator. """
    def __init__(self):
        """ ProgramExitException constructor. """
        super().__init__(0)
    def __str__(self):
        """ Message getter. """
        return "Input source code has exitted."



class MyException(Exception):
    """ User defined exception. """
    def __init__(self, msg, code):
        """ MyException constructor. """
        self.msg = msg
        self.code = code
    def __str__(self):
        """ Message getter. """
        return self.msg
    def GetCode(self):
        """ Code getter. """
        return self.code


class ParameterException(MyException):
    """ Parameter error. """
    def __init__(self, msg):
        """ ParameterException constructor. """
        super().__init__(msg, 10)

class InputFileException(MyException):
    """ Input file error. """
    def __init__(self, msg):
        """ InputFileException constructor. """
        super().__init__(msg, 11)

class OutputFileException(MyException):
    """ Output file error. """
    def __init__(self, msg):
        """ OutputFileException constructor. """
        super().__init__(msg, 12)

class InternalException(MyException):
    """ Unexpected internal error. """
    def __init__(self, msg):
        """ InternalException constructor. """
        super().__init__(msg, 99)

class XMLException(MyException):
    """ XML format error. """
    def __init__(self, msg):
        """ XMLException constructor. """
        super().__init__(msg, 31)

class SyntaxException(MyException):
    """ Syntax error. """
    def __init__(self, msg):
        """ SyntaxException constuctor. """
        super().__init__(msg, 32)

class SemanticException(MyException):
    """ Semantic error. """
    def __init__(self, msg):
        """ SemanticException constructor. """
        super().__init__(msg, 52)

class OperandException(MyException):
    """ Operand error. """
    def __init__(self, msg):
        """ OperandException constructor. """
        super().__init__(msg, 53)

class UndefinedVariableException(MyException):
    """ Undefined variable error. """
    def __init__(self, msg):
        """ UndefinedVariableException constructor. """
        super().__init__(msg, 54)

class UndefinedFrameException(MyException):
    """ Undefined frame error. """
    def __init__(self, msg):
        """ UndefinedFrameException constructor. """
        super().__init__(msg, 55)

class MissingValueException(MyException):
    """ Missing value error. """
    def __init__(self, msg):
        """ MissingValueException constructor. """
        super().__init__(msg, 56)

class ZeroDivideException(MyException):
    """ Zero divide error. """
    def __init__(self, msg):
        """ ZeroDivideException constructor. """
        super().__init__(msg, 57)

class StringException(MyException):
    """ String error. """
    def __init__(self, msg):
        """ StringException constructor. """
        super().__init__(msg, 58)
