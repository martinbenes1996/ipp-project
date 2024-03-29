
"""
Model module.

This module is an interface for Model operations (with Frames etc.).

Package: int_model.py
Author: xbenes49
Copyright: Martin Benes (c) 2018
"""

import int_frame as Types # Frame, StackFrame
import int_error as Err # exceptions
import sys

TF = None # Frame
GF = Types.Frame() # Frame
LF = Types.StackFrame() # StackFrame


def CreateFrame():
    """ Creates Frame, saves it to TF. """
    global TF
    TF = Types.Frame()

def PushFrame():
    """ Pushes Frame onto LF. Aims LF operations on it.
        Will throw exception, if TF is not defined. """
    global TF
    global LF
    if TF is not None:
        LF.PushFrame(TF)
        TF = None
    else:
        raise Err.UndefinedFrameException("TF is not defined!")

def PopFrame():
    """ Pops Frame from LF, saves it to TF.
        Will throw exception, if LF is empty. """
    global TF
    global LF
    TF = LF.PopFrame()

def PrintModel():
    """ Makes Model printable. """
    global TF
    global GF
    global LF
    result = "Model:\n| TF: "
    if TF is not None: result += repr(TF)
    print(result + "\n| GF: " + repr(GF) + "\n| LF: " + repr(LF), file=sys.stderr)
