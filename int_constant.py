
"""
Constant module

This module implements constants as arguments and data containers
for Variable objects.

Package: int_constant.py
Author: xbenes49
Copyright: Martin Benes (c) 2018
"""

import int_error as Err

class Constant:
    """ This is Constant class. """

    def __init__(self, value):
        """ Constructor of Constant class. Shouldn't be called. """
        self.value = value

    def GetValue(self):
        """ Returns value.
            Throws exception, if not initialized. """
        if self.value is not None:
            return self.value
        else:
            raise Err.MissingValueException('value not assigned')

    def GetType(self):
        """ Returns type. """
        return None
    def Type(self):
        """ Returns string of type. """
        raise Err.SemanticException('invalid operation')

    def ToString(self):
        """ INT2CHAR operation. """
        raise Err.StringException('invalid operation')
    def ToInt(self):
        """ STRI2INT operation. """
        raise Err.StringException('invalid operation')
    def Concatenate(self, c):
        """ CONCAT operation. """
        raise Err.StringException('incompatible typess')

    def __add__(self, c):
        """ ADD operation. """
        raise Err.SemanticException('invalid operation')
    def __sub__(self, c):
        """ SUB operation. """
        raise Err.SemanticException('invalid operation')
    def __mul__(self, c):
        """ MUL operation. """
        raise Err.SemanticException('invalid operation')
    def __floordiv__(self, c):
        """ IDIV operation. """
        raise Err.SemanticException('invalid operation')
    def __lt__(self, c):
        """ LT operation. """
        raise Err.SemanticException('invalid operation')
    def __gt__(self, c):
        """ GT operation. """
        raise Err.SemanticException('invalid operation')
    def __eq__(self, c):
        """ EQ operation. """
        raise Err.SemanticException('invalid operation')
    def __not__(self):
        """ NOT operation. """
        raise Err.SemanticException('invalid operation')
    def __len__(self):
        """ STRLEN operation. """
        raise Err.StringException('invalid operation')
    def __setitem__(self, pos, c):
        """ SETCHAR operation. """
        raise Err.StringException('invalid operation')
    def __getitem__(self, pos):
        """ GETCHAR operation. """
        raise Err.StringException('invalid operation')


    def __str__(self):
        """ Makes Constant printable. """
        return "Constant: " + repr(self)
    def __repr__(self):
        """ Makes Constant representable as str. """
        if self.value == None:
            return 'None'
        else:
            return str(self.GetValue())

class BoolConstant(Constant):
    """ This is BoolConstant class. """

    def __init__(self, value):
        """ Constructor of BoolConstant class. """
        if type(value) == bool:
            super().__init__(value)
        else:
            if value == 'true': super().__init__( True )
            elif value == 'false': super().__init__( False )
            else:
                raise Err.SemanticException('bool value expected')

    def GetType(self):
        """ Type getter. """
        return bool
    def Type(self):
        """ Returns string of type. """
        return 'bool'

    def __lt__(self, c):
        """ LT operation. """
        if c.GetType() == bool:
            return BoolConstant(not self.GetValue() and c.GetValue())
        else:
            raise Err.SemanticException('incompatible types')
    def __gt__(self, c):
        """ GT operation. """
        if c.GetType() == bool:
            return BoolConstant(self.GetValue() and not c.GetValue())
        else:
            raise Err.SemanticException('incompatible types')
    def __eq__(self, c):
        """ EQ operation. """
        if c.GetType() == bool:
            return BoolConstant(self.GetValue() == c.GetValue())
        else:
            raise Err.SemanticException('incompatible types')
    def __not__(self):
        """ NOT operation. """
        return BoolConstant(not self.GetValue())

class IntConstant(Constant):
    """ This is IntConstant class. """

    def __init__(self, value):
        """ Constructor of IntConstant class. """
        if type(value) == int:
            super().__init__(value)
        else:
            try:
                super().__init__( int(value) )
            except:
                raise Err.SemanticException('int value expected')

    def GetType(self):
        """ Type getter. """
        return int
    def Type(self):
        """ Returns string of type. """
        return 'int'

    def ToString():
        try:
            return StringConstant( chr(self.GetValue()) )
        except:
            raise Err.StringException('invalid operation')

    def __add__(self, c):
        """ ADD operation. """
        if c.GetType() == int:
            return IntConstant(self.GetValue() + c.GetValue())
        elif c.GetType() == float:
            return FloatConstant(float(self.GetValue()) + c.GetValue())
        else:
            raise Err.SemanticException('incompatible types')
    def __sub__(self, c):
        """ SUB operation. """
        if c.GetType() == int:
            return IntConstant(self.GetValue() - c.GetValue())
        elif c.GetType() == float:
            return FloatConstant(float(self.GetValue()) - c.GetValue())
        else:
            raise Err.SemanticException('incompatible types')
    def __mul__(self, c):
        """ MUL operation. """
        if c.GetType() == int:
            return IntConstant(self.GetValue() * c.GetValue())
        elif c.GetType() == float:
            return FloatConstant(float(self.GetValue()) * c.GetValue())
        else:
            raise Err.SemanticException('incompatible types')
    def __floordiv__(self, c):
        """ IDIV operation. """
        if c.GetType() == int:
            return IntConstant(self.GetValue() // c.GetValue())
        elif c.GetType() == float:
            return FloatConstant(float(self.GetValue()) // c.GetValue())
        else:
            raise Err.SemanticException('incompatible types')
    def __lt__(self, c):
        """ LT operation. """
        if c.GetType() == int or c.GetType() == float:
            return BoolConstant(self.GetValue() < c.GetValue())
        else:
            raise Err.SemanticException('incompatible types')
    def __gt__(self, c):
        """ GT operation. """
        if c.GetType() == int or c.GetType() == float:
            return BoolConstant(self.GetValue() > c.GetValue())
        else:
            raise Err.SemanticException('incompatible types')
    def __eq__(self, c):
        """ EQ operation. """
        if c.GetType() == int or c.GetType() == float:
            return BoolConstant(self.GetValue() == c.GetValue())
        else:
            raise Err.SemanticException('incompatible types')


class StringConstant(Constant):
    """ This is StringConstant class. """

    def __init__(self, value):
        """ Constructor of StringConstant class. """
        if type(value) == str:
            super().__init__(bytes(value, "utf-8").decode("unicode_escape"))
        else:
            raise Err.SemanticException('string value expected')

    def GetType(self):
        """ Type getter. """
        return str
    def Type(self):
        """ Returns string of type. """
        return 'string'

    def Concatenate(self, c):
        """ CONCAT method. """
        if c.GetType() == str:
            return StringConstant( self.value + c.GetValue() )
        else:
            raise Err.StringException('incompatible types')

    def ToInt(self, pos):
        """ STRI2INT operation. """
        return ord(self[pos].GetValue())

    def __len__(self):
        """ STRLEN operation. """
        return len(self.value)
    def __setitem__(self, pos, c):
        """ SETCHAR operation. """
        if pos.GetType() == int and c.GetType() == str:
            if pos.GetValue() < len(self):
                self.value = self.GetValue()[0:pos.GetValue()] + c.GetValue()[0] + self.GetValue()[pos.GetValue()+1:]
            else:
                raise Err.StringException('index out of range')
        else:
            raise Err.SemanticException('incompatible types')
    def __getitem__(self, pos):
        """ GETCHAR operation. """
        if pos.GetType() == int:
            if pos.GetValue() < len(self):
                return StringConstant(self.GetValue[pos.GetValue()])
            else:
                raise Err.StringException('index out of range')
        else:
            raise Err.SemanticException('incompatible types')

    def __repr__(self):
        """ Makes Constant representable as str. """
        return '\'' + super().__repr__() + '\''