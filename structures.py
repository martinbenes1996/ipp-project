
"""
Structures module

This module contains the most low-level classes, used as data containers.
It is needed, because the Exceptions are raised, when a certain operations
are done in certain state of data.

Package: structures.py
Author: xbenes49
Copyright: Martin Benes (c) 2018
"""

import error as Err

class Stack:
    """ This is Stack class. """

    def __init__(self):
        """ Constructor. Creates stack as list. """
        self.data = []

    def Push(self, item):
        """ Pushes item onto stack. """
        self.data.append(item)

    def Pop(self):
        """ Returns top item and pops it.
            Will throw exception, if empty. """
        item = self.Top()
        del self.data[-1]
        return item

    def Top(self):
        """ Returns top item.
            Will throw exception, if empty. """
        try:
            return self.data[-1]
        except:
            raise Err.UndefinedFrameException('empty framestack')

    def __iter__(self):
        """ Makes Stack iterable. """
        return iter(reversed(self.data))
    def __repr__(self):
        """ Makes Stack representable as str. """
        return ', '.join( repr(it) for it in reversed(self.data) )
    def __str__(self):
        """ Makes Stack printable. """
        return "Stack: " + repr(self)



class Map:
    """ This is Map class. """

    def __init__(self):
        """ Constructor. Creates map as a dict. """
        self.data = dict()

    def Add(self, key, val):
        """ Adds key, sets to None.
            Will throw exception, if the key exists. """
        if key not in self.data:
            self.data[key] = val
        else:
            raise Exception()

    def Update(self, key, item):
        """ Updates data.
            Will throw exception, if the key does not exist. """
        if key in self.data:
            self.data[key] = item
        else:
            raise Exception()

    def Delete(self, key):
        """ Deletes key.
            Will throw exception, if the key does not exist. """
        if key in self.data:
            del self.data[key]
        else:
            raise Exception()

    def Get(self, key):
        """ Returns data.
            Will throw exception, if key is not initialized. """
        if key in self.data:
            return self.data[key]
        # otherwise
        raise Exception()

    def __iter__(self):
        """ Makes Map iterable. """
        return iter(self.data)
    def __repr__(self):
        """ Makes Map representable as str. """
        s = ''
        for k in self.data:
            s += '[' + k + ': ' + repr(self.data[k])+ ']'
        return s #', '.join(self.data.keys())
    def __str__(self):
        """ Makes Map printable. """
        return "Map: " + repr(self)
