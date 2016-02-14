from game import *

class OutsCalculator:
    def get_outs(self, game):
        outs = []
        self.game = game
        self.board = game.board
        self.hand = game.hand
        best_hand = self.calculate_best_hand()

    def calculate_best_hand(self):
        g = self.game
        g.quads = has_quads()
        g.straight = has_straight()
        g.set = has_set()
        g.two_pair = has_two_pair()
        g.pair = has_pair()

    def has_straight(self):
        f = self.hand.first_card
        s = self.hand.second_card
        if f.face != 5 and f.face != 10 and s.face != 5 and s.face != 10:
            return False
        # sort
        return False

    def has_set(self):
        for card in self.board:
            if self.hand.first_card.face == card.face:
                return True
        return False

    def has_quads(self):
        s = 0
        for card in self.board:
            if self.hand.first_card.face == card.face:
                s += 1
        return s == 2
