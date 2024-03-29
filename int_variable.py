
"""
Variable module

This module implements Variable and its operations.

Package: int_variable.py
Author: xbenes49
Copyright: Martin Benes (c) 2018
"""

import int_constant as Const
import int_error as Err

class Variable():
    """ Class, that extends constant (value) to variable (may be changed). """
    varcount = 0
    def __init__(self, name = '', loc = ''):
        """ Variable constructor. """
        self.value = Const.Constant(None)
        self.name = name
        self.loc = loc

    def Set(self, c):
        """ Moves value and type of given constant to the variable. """
        if c.GetType() == None:
            raise Err.MissingValueException('missing value')

        if self.GetType() == None:
            Variable.varcount += 1

        if c.GetType() == int:
            self.value = Const.IntConstant( c.GetValue() )
        elif c.GetType() == str:
            self.value = Const.StringConstant( c.GetValue() )
        elif c.GetType() == bool:
            self.value = Const.BoolConstant( c.GetValue() )
        else:
            raise Err.SemanticException('incompatible types')

    def GetType(self):
        """ Type getter. """
        return self.value.GetType()
    def Type(self):
        """ Returns string of type. """
        return self.value.Type()

    def GetValue(self):
        """ Value getter. """
        return self.value.GetValue()

    def GetLocation(self):
        """ Location getter. """
        return self.loc

    def GetName(self):
        """ Name getter. """
        return self.name



    def __add__(self, c):
        """ ADD operation. """
        if self.GetType() == None or c.GetType() == None:
            raise Err.MissingValueException('invalid operation')
        return self.value.__add__(c)
    def __sub__(self, c):
        """ SUB operation. """
        if self.GetType() == None or c.GetType() == None:
            raise Err.MissingValueException('invalid operation')
        return self.value.__sub__(c)
    def __mul__(self, c):
        """ MUL operation. """
        if self.GetType() == None or c.GetType() == None:
            raise Err.MissingValueException('invalid operation')
        return self.value.__mul__(c)
    def __floordiv__(self, c):
        """ IDIV operation. """
        if self.GetType() == None or c.GetType() == None:
            raise Err.MissingValueException('invalid operation')
        return self.value.__floordiv__(c)
    def __lt__(self, c):
        """ LT operation. """
        if self.GetType() == None or c.GetType() == None:
            raise Err.MissingValueException('invalid operation')
        return self.value.__lt__(c)
    def __gt__(self, c):
        """ GT operation. """
        if self.GetType() == None or c.GetType() == None:
            raise Err.MissingValueException('invalid operation')
        return self.value.__gt__(c)
    def __eq__(self, c):
        """ EQ operation. """
        if self.GetType() == None or c.GetType() == None:
            raise Err.MissingValueException('invalid operation')
        return self.value.__eq__(c)
    def __and__(self, c):
        """ AND operation. """
        if self.GetType() == None or c.GetType() == None:
            raise Err.MissingValueException('invalid operation')
        return self.value.__and__(c)
    def __or__(self, c):
        """ OR operation. """
        if self.GetType() == None or c.GetType() == None:
            raise Err.MissingValueException('invalid operation')
        return self.value.__or__(c)
    def __not__(self):
        """ NOT operation. """
        if self.GetType() == None:
            raise Err.MissingValueException('invalid operation')
        return self.value.__not__()
    def __len__(self):
        """ STRLEN operation. """
        if self.GetType() == None:
            raise Err.MissingValueException('invalid operation')
        return self.value.__len__()
    def __setitem__(self, pos, c):
        """ SETCHAR operation. """
        if self.GetType() == None or pos.GetType() == None or c.GetType() == None:
            raise Err.MissingValueException('invalid operation')
        return self.value.__setitem__(pos, c)
    def __getitem__(self, pos):
        """ GETCHAR operation. """
        if self.GetType() == None or pos.GetType() == None:
            raise Err.MissingValueException('invalid operation')
        return self.value.__getitem__(pos)

    def __repr__(self):
        """ Makes Variable representable as str. """
        if self.GetType() == None:
            raise Err.MissingValueException('invalid operation')
        return repr(self.value)
    def __str__(self):
        """ Makes Variable printable. """
        return "Variable: " + repr(self)
