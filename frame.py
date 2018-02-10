
"""
Frame module

This module implements classes for Model module.

Package: frame.py
Author: xbenes49
Copyright: Martin Benes (c) 2018
"""

import structures as Structs # Map, Stack

class Frame:
    """ This is Frame class. """

    def __init__(self):
        """ Constructor of Frame. Represents Frame as Map. """
        self.vars = Structs.Map()

    def DefVar(self, name):
        """ Defines top Frame variable.
            Will throw exception, if exists. """
        try:
            self.vars.Add(name)
        except:
            raise Exception("Variable" + name + "already exists!")

    def GetValue(self, name):
        """ Returns top Frame variable value.
            Will throw exception, if not initialized. """
        try:
            return self.vars.Get(name).GetValue()
        except:
            raise Exception("Variable " + name + " not initialized!")

    def GetType(self, name):
        """ Returns top Frame variable type.
            Will throw exception, if not initialized. """
        try:
            return self.vars.Get(name).GetType()
        except:
            raise Exception("Variable " + name + " not initialized!")

    def Set(self, name, c):
        """ Initializes top Frame variable.
            Will throw exception, if does not exist. """
        try:
            self.vars.Update(name, c)
        except:
            raise Exception("Variable " + name + " not defined!")

    def __iter__(self):
        """ Makes Frame iterable. """
        return iter(self.vars)
    def __repr__(self):
        """ Makes Frame representable as str. """
        return repr(self.vars)
    def __str__(self):
        """ Makes Frame printable. """
        return "Frame: " + repr(self)




class StackFrame(Frame):
    """ This is StackFrame class. """

    def __init__(self):
        """ Constructor of StackFrame. Represents StackFrame as Stack. """
        self.frames = Structs.Stack()

    def PushFrame(self, frame):
        """ Pushes Frame onto StackFrame. """
        self.frames.Push(frame)

    def PopFrame(self):
        """ Pops Frame and returns it.
            Will throw exception, if empty. """
        try:
            return self.frames.Pop()
        except:
            raise Exception("Empty stackframe!")

    def DefVar(self, name):
        """ Creates new top Frame variable.
            Will throw exception, if empty. """
        try:
            self.frames.Top().DefVar(name)
        except:
            raise Exception("Empty stackframe!")

    def GetValue(self, name):
        """ Returns top Frame variable value.
            Will throw exception, if empty, or not initialized. """
        try:
            return self.frames.Top().GetValue(name)
        except:
            raise Exception("Empty stackframe!")

    def GetType(self, name):
        """ Returns top Frame variable type.
            Will throw exception, if empty, or not initialized. """
        try:
            return self.frames.Top().GetType(name)
        except:
            raise Exception("Empty stackframe!")

    def Set(self, name, c):
        """ Initializes top Frame variable.
            Will throw exception, if does not exist. """
        try:
            self.frames.Top().Set(name, c)
        except:
            raise Exception("Empty stackframe!")

    def __iter__(self):
        """ Makes StackFrame iterable. """
        return iter(self.frames)
    def __repr__(self):
        """ Makes StackFrame representable as str. """
        return repr(self.frames)
    def __str__(self):
        """ Makes StackFrame printable. """
        return "StackFrame: " + repr(self)
