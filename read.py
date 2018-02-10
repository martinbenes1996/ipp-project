
import xml.etree.ElementTree as ET

# there will be variable saved inside
# it must be inherited from the same (meaning constant and variable)
# think also about types (and implicit converting)
# so no strings pushed here directly
# maybe better to start from ground (not from top as here)
# morning Martin greets you :)

class ProgramExitException(Exception):
    def __init__(self):
        pass
    def __str__(self):
        return ""

class Argument:
    def __init__(self, type):
        pass

class Instruction:
    def __init__(self, opcode, args):
        self.opcode = opcode
        self.args = []
        for a in args:
            self.args.append( Argument(a) )

    def Execute(self):
        return 0



class Reader:

    def __init__(self, name):
        self.file = ET.parse(name)
        self.root = self.file.getroot()

    def PrintXML(self):
        print(self.root[1][0].attrib)

    def GetInstruction(self, order):
        try:
            return Instruction( self.root[order].attrib['opcode'] )
        except:
            raise Exception('Corrupted XML')
