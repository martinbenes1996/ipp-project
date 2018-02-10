
import sys
import xml.etree.ElementTree as ET

import error as Err
import model as Model
import structures as Struct
import value as Value

class Run:
    def __init__(self):
        self.ic = 0
        self.labels = dict()
        self.callstack = Struct.Stack()
        self.datastack = Struct.Stack()

    def GetIC(self):
        return self.ic
    def SetIC(self, ic):
        self.ic = ic
    def IncrementIC(self):
        self.ic += 1

    def AddLabel(self, name, pos):
        self.labels[name] = pos
    def GetLabelPos(self, name):
        try:
            return self.labels[name]
        except:
            raise Err.SemanticException('Not known label: ' + name)

    def CallFrom(self, pos):
        self.callstack.Push(pos)
    def ReturnTo(self):
        self.callstack.Pop()

    def Pushs(self, val):
        self.datastack.Push(val)
    def Pops(self):
        try:
            self.datastack.Pop()
        except:
            raise Err.MissingValueException('Empty data stack')

run = Run()

class Instruction:

    def __init__(self, obj):
        try:
            self.order = obj.attrib['order']
        except:
            raise Err.XMLException('ORDER parameter not present in instruction ???!')

        try:
            self.opcode = obj.attrib['opcode']
        except Exception:
            raise XMLException('OPCODE parameter not present in instruction'+self.order)

        self.obj = obj

    def PushFrame(self):
        global run
        Model.PushFrame()
        run.IncrementIC()

    def CreateFrame(self):
        global run
        Model.CreateFrame()
        run.IncrementIC()

    def PopFrame(self):
        global run
        Model.CreateFrame()
        run.IncrementIC()

    def Return(self):
        global run
        try:
            run.SetIC( run.ReturnTo() )
        except:
            # wait for forum
            raise SyntaxException('RETURN without previous CALL!')

    def Pushs(self):
        self.stack.Push(self.arg1)
        run.IncrementIC()

    def Write(self):
        print(self.arg1.Write())
        run.IncrementIC()

    def DPrint(self):
        print(self.arg1.Write(), file=sys.stderr)
        run.IncrementIC()

    def DefVar(self):
        if self.arg1.GetLocation() == 'GF':
            Model.GF.DefVar( self.arg1.GetName() )
        elif self.arg1.GetLocation() == 'LF':
            Model.LF.DefVar( self.arg1.GetName() )
        elif self.arg1.GetLOcation() == 'TF':
            Model.TF.DefVar( self.arg1.GetName() )
        run.IncrementIC()



    def ParseConstant(self, obj):
        """ Parses constant (variable or constant) and returns it. """
        # type
        try:
            t = obj.attrib['type']
        except:
            raise XMLException('OPCODE parameter not present in instruction '+self.order)
        if t == 'var':
            return self.ParseVariable(obj)

        # text
        try:
            val = obj.text()
        except:
            raise XMLException('No text in instruction '+self.order)

        return Value.Constant(val, t)


    def ParseVariable(self, obj):
        """ Parses constant (variable or constant) and returns it. """
        # type
        try: t = obj.attrib['type']
        except: raise XMLException('OPCODE parameter not present in instruction '+self.order)

        # text
        try: val = obj.text()
        except: raise XMLException('No text in instruction'+self.order)

        # name and location
        varname = re.compile(r'^(?<=((LF)|(GF)|(TF))@)[-a-zA-Z_$&%*]+$')
        loc = re.compile(r'^((LF)|(GF)|(TF))(?=@[-a-zA-Z_$&%*]+)$')
        if varname.search(val) is None or loc.search(val) is None:
            raise SyntaxException("Variable name expected: instruction "+self.order)

        # return
        if t != 'var':
            raise OperandException('Variable expected: instruction ' + self.order)
        else:
            return Value.Variable( loc.search(val).group(), varname.search(val).group())

    def Decode(self):
        obj = self.obj

        # PUSHFRAME
        if self.opcode == 'PUSHFRAME':
            return self.PushFrame
            if len(obj) is not 0:
                raise Err.OperandException('Extra operands: instruction ' + self.order + ': ' + self.opcode)

        # CREATEFRAME
        elif self.opcode == 'CREATEFRAME':
            return self.CreateFrame
            # extra operands
            if len(obj) is not 0: raise Err.OperandException('Extra operands: instruction ' + self.order + ': ' + self.opcode)

        # POPFRAME
        elif self.opcode == 'POPFRAME':
            return self.PopFrame
            # extra operands
            if len(obj) is not 0: raise Err.OperandException('Extra operands: instruction ' + self.order + ': ' + self.opcode)

        # RETURN
        elif self.opcode == 'RETURN':
            return self.Return
            # extra operands
            if len(obj) is not 0: raise Err.OperandException('Extra operands: instruction ' + self.order + ': ' + self.opcode)

        # BREAK
        elif self.opcode == 'BREAK':
            self.run = Model.PrintModel
            # extra operands
            if len(obj) is not 0: raise Err.OperandException('Extra operands: instruction ' + self.order + ': ' + self.opcode)


        # PUSHS
        elif self.opcode == 'PUSHS':
            try:
                self.arg1 = ParseConstant(obj[0])
                return self.Pushs
            except:
                raise Err.OperandException('Missing operands: instruction ' + self.order + ': ' + self.opcode)
            # extra operands
            if len(obj) is not 1: raise Err.OperandException('Extra operands: instruction ' + self.order + ': ' + self.opcode)

        # WRITE
        elif self.opcode == 'WRITE':
            try:
                self.arg1 = ParseConstant(obj[0])
                return self.Write
            except:
                raise Err.OperandException('Missing operands: instruction ' + self.order + ': ' + self.opcode)
            # extra operands
            if len(obj) is not 1: raise Err.OperandException('Extra operands: instruction ' + self.order + ': ' + self.opcode)

        # DPRINT
        elif self.opcode == 'DPRINT':
            try:
                self.arg1 = ParseConstant(obj[0])
                return self.DPrint
            except:
                raise Err.OperandException('Missing operands: instruction ' + self.order + ': ' + self.opcode)
            # extra operands
            if len(obj) is not 1: raise Err.OperandException('Extra operands: instruction ' + self.order + ': ' + self.opcode)

        # DEFVAR
        elif self.opcode == 'DEFVAR':
            try:
                self.arg1 = ParseVariable(obj[1])
                return self.DefVar
            except:
                raise Err.OperandException('Missing operands: instruction ' + self.order + ': ' + self.opcode)
            # extra operands
            if len(obj) is not 1: raise Err.OperandException('Extra operands: instruction ' + self.order + ': ' + self.opcode)




        # wrong opcode
        else:
            raise Err.SyntaxException('Unknown opcode: ' + self.opcode)









class Reader:

    def __init__(self, name):
        try:
            self.file = ET.parse(name)
            self.root = self.file.getroot()
        except:
            raise Err.XMLException('Corrupted XML!')

    def PrintXML(self):
        print(self.root[1][0].attrib)

    def Decode(self):
        global run
        if len(self.root) <= run.GetIC():
            raise Err.ProgramExitException()

        i = Instruction( self.root[run.GetIC()] )
        return i.Decode()
