
"""
Frame module

This module implements classes for Model module.

Package: frame.py
Author: xbenes49
Copyright: Martin Benes (c) 2018
"""

import structures as Structs # Map, Stack
import error as Err

class Frame:
    """ This is Frame class. """

    def __init__(self):
        """ Constructor of Frame. Represents Frame as Map. """
        self.vars = Structs.Map()

    def DefVar(self, name, c):
        """ Defines variable.
            Will throw exception, if exists. """
        try:
            self.vars.Add(name, c)
        except:
            raise Exception("variable" + name + "already exists")

    def GetValue(self, name):
        """ Returns variable value.
            Will throw exception, if not initialized. """
        try:
            return self.vars.Get(name).GetValue()
        except:
            raise Exception("variable " + name + " not initialized")

    def GetType(self, name):
        """ Returns variable type.
            Will throw exception, if not initialized. """
        try:
            return self.vars.Get(name).GetType()
        except:
            raise Exception("variable " + name + " not initialized")

    def GetVariable(self, name):
        """ Returns variable.
            Will throw exception, if does not exist. """
        try:
            return self.vars.Get(name)
        except:
            raise Exception("variable " + name + " not defined")

    def Set(self, name, c):
        """ Initializes top Frame variable.
            Will throw exception, if does not exist. """
        try:
            self.vars.Get(name).Set(c)
        except:
            raise Exception("variable " + name + " not defined!")

    def __iter__(self):
        """ Makes Frame iterable. """
        return iter(self.vars)
    def __repr__(self):
        """ Makes Frame representable as str. """
        return '{' + repr(self.vars) + '}'
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
        except IndexError:
            raise Err.UndefinedFrameException("Empty stackframe!")

    def DefVar(self, name, c):
        """ Creates new top Frame variable.
            Will throw exception, if empty. """
        try:
            self.frames.Top().DefVar(name, c)
        except AttributeError:
            raise Err.UndefinedFrameException("Empty stackframe!")


    def GetValue(self, name):
        """ Returns top Frame variable value.
            Will throw exception, if empty, or not initialized. """
        try:
            return self.frames.Top().GetValue(name)
        except AttributeError:
            raise Err.UndefinedFrameException("Empty stackframe!")

    def GetType(self, name):
        """ Returns top Frame variable type.
            Will throw exception, if empty, or not initialized. """
        try:
            return self.frames.Top().GetType(name)
        except AttributeError:
            raise Err.UndefinedFrameException("Empty stackframe!")

    def GetVariable(self, name):
        """ Returns top Frame variable.
            Will throw exception, if does not exist. """
        try:
            return self.frames.Top().GetVariable(name)
        except:
            raise Exception("Empty stackframe!")

    def Set(self, name, c):
        """ Initializes top Frame variable.
            Will throw exception, if does not exist. """
        try:
            self.frames.Top().Set(name, c)
        except AttributeError:
            raise Err.UndefinedFrameException("Empty stackframe!")

    def __iter__(self):
        """ Makes StackFrame iterable. """
        return iter(self.frames)
    def __repr__(self):
        """ Makes StackFrame representable as str. """
        return repr(self.frames)
    def __str__(self):
        """ Makes StackFrame printable. """
        return "StackFrame: " + repr(self)
