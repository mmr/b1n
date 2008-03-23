#!/usr/bin/env python

__author__ = 'Marcio Ribeiro <mmr@b1n.org>'
__license__ = 'BSD License'
__copyright__ = 'Copyright 2008 Marcio Ribeiro'

from calculator import *
from game import *

class OutsGame:
    def run(self):
        def w(m):
            import sys
            sys.stdout.write(m.__str__())

        self.game = Game()
        while True:
            if self.game.is_in_preflop():
                w(self.game.hand)
                self.game.flop()
                w(self.game.board)
                continue
            elif self.game.is_in_flop():
                self.game.turn()
            elif self.game.is_in_turn():
                self.game.river()

            w(" ")
            w(self.game.last_card)

            if self.game.is_in_river():
                self.game = Game()
                print ""
                #print "."*30

def main(args):
    og = OutsGame()
    og.run()

if __name__ == "__main__":
    import sys
    main(sys.argv)

