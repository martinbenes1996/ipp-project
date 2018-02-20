
"""
Read module

This module contains the class Reader, which reads XML and generates objects
of class Instruction. They decode instruction and returns the method,
a representation of execution phase of the instruction.

Package: int_read.py
Author: xbenes49
Copyright: Martin Benes (c) 2018
"""

import re
import sys
import xml.etree.ElementTree as ET

import int_error as Err
import int_model as Model
import int_structures as Struct

from int_constant import *
from int_variable import *

class Run:
    """ This is Run class. """

    def __init__(self):
        """ Constructor of Run class. It sets the defaults only. """
        self.pc = 0
        self.labels = dict()
        self.callstack = Struct.Stack()
        self.datastack = Struct.Stack()
        self.bypass = False
        self.bypass_stop = ""

    def GetPC(self):
        """ Program counter getter."""
        return self.pc
    def SetPC(self, pc):
        """ Program counter setter. """
        self.pc = pc
    def IncrementPC(self):
        """ Increments program counter. """
        self.pc += 1

    def AddLabel(self, name, pos):
        """ Adds label to the table.
            Will raise exception, when exists. """
        try:
            self.labels[name] = pos
        except:
            raise Err.SemanticException('Label ' + name + ' already exists.')
    def GetLabelPos(self, name):
        """ Gets label position.
            Will raise exception, when it does not exist. """
        try:
            return self.labels[name]
        except:
            raise Err.SemanticException('Not known label: ' + name)

    def PushPC(self):
        """ Pushes program counter position to the callstack. """
        self.callstack.Push(self.pc)
    def PopPC(self):
        """ Pops program counter position from the callstack. """
        try:
            self.pc = self.callstack.Pop()
        except:
            raise Err.SyntaxException('Empty call stack!')
    def PrintCallStack(self):
        """ Prints callstack. """
        print("CallStack: " + repr(self.callstack) )


    def Pushs(self, item):
        """ Pushes value to the datastack. """
        self.datastack.Push(item)
    def Pops(self):
        """ Pops and returns top item from the datastack. """
        try:
            return self.datastack.Pop()
        except:
            raise Err.MissingValueException('Empty data stack!')
    def PrintDataStack(self):
        """ Prints datastack. """
        print("DataStack: " + repr(self.datastack) )


# Run object
run = Run()




class Instruction:
    """ This is Instruction class. """

    def __init__(self, obj):
        """ Constructor of Instruction class. It recieves its XML representation. """
        # order
        try:
            self.order = obj.attrib['order']
        except:
            raise Err.XMLException('order parameter not present')
        # opcode
        try:
            self.opcode = obj.attrib['opcode']
        except:
            raise XMLException('opcode parameter not present')
        # save XML representation
        self.obj = obj


    def GetFrame(self, var):
        if var.GetLocation() == 'GF':
            return Model.GF
        elif var.GetLocation() == 'LF':
            return Model.LF
        elif var.GetLocation() == 'TF':
            if Model.TF == None:
                raise Err.UndefinedFrameException("TF not defined.")
            return Model.TF
        else:
            raise Err.UndefinedFrameException('unknown frame')

    def GetVariable(self, var):
        self.GetFrame(var).GetVariable(var.GetName())

    # frames, function calls
    def Move(self):
        """ MOVE operation. """
        self.arg1.Set(self.arg2)

    def CreateFrame(self):
        """ CREATEFRAME operation. """
        Model.CreateFrame()
    def PushFrame(self):
        """ PUSHFRAME operation. """
        Model.PushFrame()
    def PopFrame(self):
        """ POPFRAME operation. """
        Model.PopFrame()
    def DefVar(self):
        """ DEFVAR operation. """
        self.GetFrame(self.arg1).DefVar( self.arg1.GetName(), self.arg1 )
    def Call(self):
        """ CALL operation. """
        global run
        pos = run.GetLabelPos(self.arg1)
        run.PushPC()
        run.SetPC(pos+1)
    def Return(self):
        """ RETURN operation. """
        global run
        run.PopPC()

    # data stack
    def Pushs(self):
        """ PUSHS operation. """
        global run
        run.Pushs(self.arg1)
    def Pops(self):
        """ POPS operation. """
        global run
        self.arg1.Set( run.Pops() )

    # operations
    def Add(self):
        """ ADD operation. """
        self.arg1.Set( self.arg2 + self.arg3 )
    def Sub(self):
        """ SUB operation. """
        self.arg1.Set( self.arg2 - self.arg3 )
    def Mul(self):
        """ MUL operation. """
        self.arg1.Set( self.arg2 * self.arg3 )
    def IDiv(self):
        """ IDIV operation. """
        self.arg1.Set( self.arg2 // self.arg3 )
    def Lt(self):
        """ LT operation. """
        self.arg1.Set( self.arg2 < self.arg3 )
    def Gt(self):
        """ GT operation. """
        self.arg1.Set( self.arg2 > self.arg3 )
    def Eq(self):
        """ EQ operation. """
        self.arg1.Set( self.arg2 == self.arg3 )
    def And(self):
        """ AND operation. """
        self.arg1.Set( self.arg2 and self.arg3 )
    def Or(self):
        """ OR operation. """
        self.arg1.Set( self.arg2 or self.arg3 )
    def Not(self):
        """ NOT operation"""
        self.arg1.Set( not self.arg2 )
    def Int2Char(self):
        """ INT2CHAR operation. """
        self.arg1.Set( self.arg2.ToString() )
    def StrI2Int(self):
        """ STRI2INT operation. """
        self.arg1.Set( self.arg2.ToInt() )

    # IO
    def Read(self):                                                             #TODO
        """ READ operation. """
        pass
    def Write(self):
        """ WRITE operation. """
        print( repr(self.arg1), end='')

    # string
    def Concatenate(self):
        """ CONCAT operation. """
        self.GetFrame(self.arg1).Set(self.arg2.Concatenate(self.arg3))
    def StrLen(self):
        """ STRLEN operation. """
        self.GetFrame(self.arg1).Set(len(self.arg2))
    def GetChar(self):
        """ GETCHAR operation. """
        self.GetFrame(self.arg1).Set(self.arg2[self.arg3])
    def SetChar(self):
        """ SETCHAR operation. """
        self.arg1[self.arg2] = self.arg3

    # types
    def Type(self):
        """ TYPE operation. """
        self.arg1.Set( StringConstant(self.arg2.Type()) )

    # control instruction
    def Label(self):
        """ LABEL operation. """
        global run
        run.AddLabel(self.arg1, run.GetPC()-1)
        if run.bypass and run.bypass_stop == self.arg1:
            run.bypass_stop = ""
            run.bypass = False
    def Jump(self):
        """ JUMP operation. """
        global run
        try:
            pos = run.GetLabelPos(self.arg1)
            run.SetPC(pos+1)
        except Err.SemanticException:
            run.bypass = True
            run.bypass_stop = self.arg1

    def JumpIfEq(self):
        """ JUMPIFEQ operation. """
        global run
        if (self.arg2 == self.arg3).GetValue() :
            try:
                pos = run.GetLabelPos(self.arg1)
                run.SetPC(pos+1)
            except Err.SemanticException:
                run.bypass = True
                run.bypass_stop = self.arg1
    def JumpIfNEq(self):
        """" JUMPIFNEQ operation. """
        global run
        if not (self.arg2 == self.arg3).GetValue() :
            try:
                pos = run.GetLabelPos(self.arg1)
                run.SetPC(pos+1)
            except Err.SemanticException:
                run.bypass = True
                run.bypass_stop = self.arg1

    # debug
    def DPrint(self):
        """ DPRINT operation. """
        print( repr(self.arg1), file=sys.stderr)
    def Break(self):
        """ BREAK operation. """
        global run
        Model.PrintModel()
        run.PrintCallStack()
        run.PrintDataStack()



    def ParseConstant(self, obj):
        """ Parses constant (variable or constant) and returns it. """
        # type
        try:
            t = obj.attrib['type']
        except:
            raise XMLException('OPCODE parameter not present in instruction')
        if t == 'var':
            return self.ParseVariable(obj)

        # text
        try:
            val = obj.text
        except:
            raise XMLException('no text in instruction')

        if t == 'int':
            return IntConstant(val)
        elif t == 'string':
            return StringConstant(val)
        elif t == 'bool':
            return BoolConstant(val)
        else:
            raise Err.SyntaxException('unsupported type')

    def ParseNewVariable(self, obj):
        """ Parses constant (variable or constant) and returns it. """
        # type
        try: t = obj.attrib['type']
        except: raise XMLException('No type in instruction '+self.order)

        # text
        try: val = obj.text
        except: raise XMLException('No text in instruction'+self.order)

        # name and location
        varname = re.compile(r'(?<=^((LF)|(GF)|(TF))@)[-a-zA-Z_$&%*]+$')
        loc = re.compile(r'^((LF)|(GF)|(TF))(?=@[-a-zA-Z_$&%*]+$)')
        if varname.search(val) is None or loc.search(val) is None:
            raise Err.SyntaxException("Variable name expected: instruction "+self.order)

        # return
        if t != 'var':
            raise Err.OperandException('Variable expected: instruction ' + self.order)
        else:
            return Variable(varname.search(val).group(), loc.search(val).group())

    def ParseVariable(self, obj):
        v = self.ParseNewVariable(obj)
        return self.GetFrame(v).GetVariable(v.GetName())


    def ParseLabel(self, obj):
        """ Parses label and returns its name. """
        # type
        try: t = obj.attrib['type']
        except: raise XMLException('OPCODE parameter not present in instruction '+self.order)

        # text
        try: val = obj.text
        except: raise XMLException('No text in instruction'+self.order)

        # name
        labelname = re.compile(r'^[-a-zA-Z_$&%*]+$')
        if labelname.search(val) is None:
            raise Err.SyntaxException('Label name expected: instruction ' + self.order)

        # return
        if t != 'label':
            raise Err.OperandException('Label expected: instruction ' + self.order)
        else:
            return labelname.search(val).group()


    def ReadOperands(self, f1 = None, f2 = None, f3 = None):
        """ Reads operands and saves it inside object. """
        if f1 != None:
            try:
                self.arg1 = f1(self.obj[0])
            except IndexError:
                raise Err.OperandException('Missing operands: instruction ' + self.order + ': ' + self.opcode)

            if f2 != None:
                try:
                    self.arg2 = f2(self.obj[1])
                except IndexError:
                    raise Err.OperandException('Missing operands: instruction ' + self.order + ': ' + self.opcode)

                if f3 != None:
                    try:
                        self.arg3 = f3(self.obj[2])
                    except IndexError:
                        raise Err.OperandException('Missing operands: instruction ' + self.order + ': ' + self.opcode)
                    if len(self.obj) is not 3: raise Err.OperandException('Extra operands: instruction ' + self.order + ': ' + self.opcode)
                else:
                    if len(self.obj) is not 2: raise Err.OperandException('Extra operands: instruction ' + self.order + ': ' + self.opcode)
            else:
                if len(self.obj) is not 1: raise Err.OperandException('Extra operands: instruction ' + self.order + ': ' + self.opcode)
        else:
            if len(self.obj) is not 0: raise Err.OperandException('Extra operands: instruction ' + self.order + ': ' + self.opcode)



    def Decode(self):
        obj = self.obj
        global run

        if self.opcode == 'LABEL':
            self.ReadOperands(self.ParseLabel)
            return self.Label
        elif run.bypass:
            return lambda *args, **kwargs: None



        # MOVE
        if self.opcode == 'MOVE':
            self.ReadOperands(self.ParseVariable, self.ParseConstant)
            return self.Move

        # CREATEFRAME
        elif self.opcode == 'CREATEFRAME':
            self.ReadOperands()
            return self.CreateFrame

        # PUSHFRAME
        elif self.opcode == 'PUSHFRAME':
            self.ReadOperands()
            return self.PushFrame

        # POPFRAME
        elif self.opcode == 'POPFRAME':
            self.ReadOperands()
            return self.PopFrame

        # DEFVAR
        elif self.opcode == 'DEFVAR':
            self.ReadOperands(self.ParseNewVariable)
            return self.DefVar

        # CALL
        elif self.opcode == 'CALL':
            self.ReadOperands(self.ParseLabel)
            return self.Call

        # RETURN
        elif self.opcode == 'RETURN':
            self.ReadOperands()
            return self.Return

        # PUSHS
        elif self.opcode == 'PUSHS':
            self.ReadOperands(self.ParseConstant)
            return self.Pushs

        # POPS
        elif self.opcode == 'POPS':
            self.ReadOperands(self.ParseVariable)
            return self.Pops

        # ADD
        elif self.opcode == 'ADD':
            self.ReadOperands(self.ParseVariable, self.ParseConstant, self.ParseConstant)
            return self.Add

        # SUB
        elif self.opcode == 'SUB':
            self.ReadOperands(self.ParseVariable, self.ParseConstant, self.ParseConstant)
            return self.Sub

        # MUL
        elif self.opcode == 'MUL':
            self.ReadOperands(self.ParseVariable, self.ParseConstant, self.ParseConstant)
            return self.Mul

        # IDIV
        elif self.opcode == 'IDIV':
            self.ReadOperands(self.ParseVariable, self.ParseConstant, self.ParseConstant)
            return self.IDiv

        # LT
        elif self.opcode == 'LT':
            self.ReadOperands(self.ParseVariable, self.ParseConstant, self.ParseConstant)
            return self.Lt

        # GT
        elif self.opcode == 'GT':
            self.ReadOperands(self.ParseVariable, self.ParseConstant, self.ParseConstant)
            return self.Gt

        # EQ
        elif self.opcode == 'EQ':
            self.ReadOperands(self.ParseVariable, self.ParseConstant, self.ParseConstant)
            return self.Eq

        # AND
        elif self.opcode == 'AND':
            self.ReadOperands(self.ParseVariable, self.ParseConstant, self.ParseConstant)
            return self.And

        # OR
        elif self.opcode == 'OR':
            self.ReadOperands(self.ParseVariable, self.ParseConstant, self.ParseConstant)
            return self.Or

        # NOT
        elif self.opcode == 'NOT':
            self.ReadOperands(self.ParseVariable, self.ParseConstant)
            return self.Not

        # INT2CHAR
        elif self.opcode == 'INT2CHAR':
            self.ReadOperands(self.ParseVariable, self.ParseConstant)
            return self.Int2Char

        # STRI2INT
        elif self.opcode == 'STRI2INT':
            self.ReadOperands(self.ParseVariable, self.ParseConstant)
            return self.StrI2Int

        # READ
        elif self.opcode == 'READ':
            self.ReadOperands(self.ParseVariable, self.ParseType)
            return self.Read

        # WRITE
        elif self.opcode == 'WRITE':
            self.ReadOperands(self.ParseConstant)
            return self.Write

        # CONCAT
        elif self.opcode == 'CONCAT':
            self.ReadOperands(self.ParseVariable, self.ParseConstant, self.ParseConstant)
            return self.Concatenate

        # STRLEN
        elif self.opcode == 'STRLEN':
            self.ReadOperands(self.ParseVariable, self.ParseConstant)
            return self.StrLen

        # GETCHAR
        elif self.opcode == 'GETCHAR':
            self.ReadOperands(self.ParseVariable, self.ParseConstant, self.ParseConstant)
            return self.GetChar

        # SETCHAR
        elif self.opcode == 'SETCHAR':
            self.ReadOperands(self.ParseVariable, self.ParseConstant, self.ParseConstant)
            return self.SetChar

        # TYPE
        elif self.opcode == 'TYPE':
            self.ReadOperands(self.ParseVariable, self.ParseConstant)
            return self.Type

        # LABEL
        elif self.opcode == 'LABEL':
            self.ReadOperands(self.ParseLabel)
            return self.Label

        # JUMP
        elif self.opcode == 'JUMP':
            self.ReadOperands(self.ParseLabel)
            return self.Jump

        # JUMPIFEQ
        elif self.opcode == 'JUMPIFEQ':
            self.ReadOperands(self.ParseLabel, self.ParseConstant, self.ParseConstant)
            return self.JumpIfEq

        # JUMPIFNEQ
        elif self.opcode == 'JUMPIFNEQ':
            self.ReadOperands(self.ParseLabel, self.ParseConstant, self.ParseConstant)
            return self.JumpIfNEq

        # DPRINT
        elif self.opcode == 'DPRINT':
            self.ReadOperands(self.ParseConstant)
            return self.DPrint

        # BREAK
        elif self.opcode == 'BREAK':
            self.ReadOperands()
            return self.Break


        # wrong opcode
        else:
            raise Err.SyntaxException('Unknown opcode: ' + self.opcode)






class Reader:
    """ This is Reader class. """

    def __init__(self, name):
        """ Constructor of Reader class. Reads XML file. """
        try:
            self.file = ET.parse(name)
            self.root = self.file.getroot()
        except:
            raise Err.XMLException('Corrupted XML!')

    def Decode(self):
        """ Decodes instruction, increments PC. """
        global run
        if len(self.root) <= run.GetPC():
            if run.bypass:
                raise Err.SemanticException('unknown label ' + self.bypass_stop)
            raise Err.ProgramExitException()

        i = Instruction( self.root[run.GetPC()] )
        run.IncrementPC()
        return i.Decode()



    def GetPC(self):
        global run
        return run.GetPC()
