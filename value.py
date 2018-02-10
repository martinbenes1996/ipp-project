

class Constant:
    """ This is Constant class. """

    def __init__(self, name, value, datatype):
        """ Constructor of Constant class. Saves value and datatype inside.
            The name is only for error messages. """
        self.name = name
        self.value = value
        self.type = datatype

    def GetValue(self):
        """ Returns value.
            Throws exception, if not initialized. """
        if self.value is not None:
            return self.value
        else:
            raise Exception("Value" + self.name + "not assigned!")

    def GetType(self):
        """ Returns type.
            Throws exception, if not initialized. """
        if self.type is None:
            raise Exception("Variable" + self.name + "not assigned!")
        else:
            return self.type

    def __str__(self):
        """ Makes Constant representable """
        return "Constant: " + repr(self)

    def __repr__(self):
        return str(self.GetValue)

class Variable(Constant):
    """ Class, that extends constant (value) to variable (may be changed). """
    def __init__(self, name, value = None, datatype = None):
        super().__init__(name, value, datatype)

    def SetValue(self, c):
        """ Sets value to given constant's value.
            Will throw exception, if the types are incompatible. """
        # different types
        if self.GetType() is not c.GetType():
            # int and float
            if self.GetType() is "int" and c.GetType() is "float":
                self.type = c.GetType()
                self.value = c.GetValue()
            elif self.GetType() is "float" and c.GetType() is "int":
                self.value = float( c.GetValue() )
            # others (not compatible)
            else:
                raise Exception(self.name + " and " + var.GetName() + " have not compatible types.")
        # same types
        else:
            self.value = var.GetValue()

    def __str__(self):
        """ Makes Variable printable. """
        return "Variable: " + repr(self)
