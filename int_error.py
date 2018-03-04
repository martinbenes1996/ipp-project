
"""
Error module.

This module includes Exception classes.

Package: int_rror.py
Author: xbenes49
Copyright: Martin Benes (c) 2018
"""


class NonErrorException(Exception):
    def __init__(self, errcode):
        self.errcode = errcode
    def __str__(self):
        return "This should be caught and ignored."
    def GetCode(self):
        return self.errcode

class HelpException(NonErrorException):
    def __init__(self):
        super().__init__(0)
    def __str__(self):
        return ""

class ProgramExitException(NonErrorException):
    def __init__(self):
        super().__init__(0)
    def __str__(self):
        return "Input source code has exitted."



class MyException(Exception):
    def __init__(self, msg, code):
        self.msg = msg
        self.code = code
    def __str__(self):
        return self.msg
    def GetCode(self):
        return self.code


class ParameterException(MyException):
    def __init__(self, msg):
        super().__init__(msg, 10)

class InputFileException(MyException):
    def __init__(self, msg):
        super().__init__(msg, 11)

class OutputFileException(MyException):
    def __init__(self, msg):
        super().__init__(msg, 12)

class InternalException(MyException):
    def __init__(self, msg):
        super().__init__(msg, 99)

class XMLException(MyException):
    def __init__(self, msg):
        super().__init__(msg, 31)

class SyntaxException(MyException):
    def __init__(self, msg):
        super().__init__(msg, 32)

class SemanticException(MyException):
    def __init__(self, msg):
        super().__init__(msg, 52)

class OperandException(MyException):
    def __init__(self, msg):
        super().__init__(msg, 53)

class UndefinedVariableException(MyException):
    def __init__(self, msg):
        super().__init__(msg, 54)

class UndefinedFrameException(MyException):
    def __init__(self, msg):
        super().__init__(msg, 55)

class MissingValueException(MyException):
    def __init__(self, msg):
        super().__init__(msg, 56)

class ZeroDivideException(MyException):
    def __init__(self, msg):
        super().__init__(msg, 57)

class StringException(MyException):
    def __init__(self, msg):
        super().__init__(msg, 58)
