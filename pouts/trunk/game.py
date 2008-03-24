__author__ = 'Marcio Ribeiro'
__license__ = 'BSD License'
__copyright__ = 'Copyright 2008 Marcio Ribeiro'

class Suit:
    """Deck suits: Spades, Hearts, Diamonds and Clubs"""
    SPADES = 0
    HEARTS = 1
    DIAMONDS = 2
    CLUBS = 3
    def __init__(self, name):
        self.name = name
    def __str__(self):
        return self.name
Suit.suits = [Suit("s"), Suit("h"), Suit("d"), Suit("c")]

class Hand:
    """Texas Hold'em hand, with first and second cards"""
    def __init__(self, first_card, second_card):
        self.first_card = first_card
        self.second_card = second_card
    def __str__(self):
        return "%s %s" % (self.first_card, self.second_card)

class Card:
    """Card, with its face and suit values"""
    def __init__(self, face, suit):
        self.face = face 
        self.suit = suit
    def __str__(self):
        f = {
            10: "T",
            11: "J",
            12: "Q",
            13: "K",
            1:  "A",
        }.get(self.face, str(self.face))
        return "%s%s" % (f, self.suit)

class Deck:
    """Deck of cards"""
    def __init__(self):
        import random
        self.cards = []
        for suit in range(4):
            for face in range(1, 14):
                self.cards.append(Card(face, Suit.suits[suit]))
        random.shuffle(self.cards)
    def hit(self):
        return self.cards.pop()

class Board:
    """Board/Community cards"""
    def __init__(self):
        self.cards = []
    def add(self, card):
        self.cards.append(card)
    def __str__(self):
        ret = "  "
        for c in self.cards:
            ret = "%s %s" % (ret, c)
        return ret

class Status:
    """Status of the game"""
    PREFLOP = "preflop"
    FLOP    = "flop"
    TURN    = "turn"
    RIVER   = "river"

class Game:
    """Texas hold'em game"""

    def __init__(self):
        self.deck = Deck()
        self.board = Board()
        self.hand = Hand(self.deck.hit(), self.deck.hit())
        self.status = Status.PREFLOP
        self.last_card = None
    def _burn(self):
        """Take a card from the Deck and burn it (dont show)"""
        self.deck.hit()
    def _hit(self):
        """Take a card from the Deck and add it to the Board"""
        self.last_card = self.deck.hit()
        self.board.add(self.last_card)
    def flop(self):
        """Burn one and hit three"""
        self._burn()
        for i in range(3):
            self._hit()
        self.status = Status.FLOP
    def turn(self):
        """Burn one and hit one"""
        self._burn()
        self._hit()
        self.status = Status.TURN
    def river(self):
        """Burn one and hit one"""
        self._burn()
        self._hit()
        self.status = Status.RIVER
    def is_in_preflop(self):
        return self.status == Status.PREFLOP
    def is_in_flop(self):
        return self.status == Status.FLOP
    def is_in_turn(self):
        return self.status == Status.TURN
    def is_in_river(self):
        return self.status == Status.RIVER
    def __str__(self):
        return "%s %s" % (self.hand, self.board)

