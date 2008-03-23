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
        return name
Suit.suits = [Suit("s"), Suit("h"), Suit("d"), Suit("c")]

class Hand:
    """Texas Hold'em hand, with first and second cards"""
    def __init__(self, first_card, second_card):
        self.first_card = first_card
        self.second_card = second_card
    def __str__(self):
        return "%s %s" % (first_card, second_card)

class Card:
    """Card, with its face and suit values"""
    def __init__(self, face, suit):
        self.face = face 
        self.suit = suit
    def __str__(self):
        face = {
            10: "T",
            11: "J",
            12: "Q",
            13: "K",
            1:  "A",
        }.get(face, str(face))
        return "%s%s" % (face, suit)

class Deck:
    """Deck of cards"""
    def __init__(self):
        import random
        self.cards = []
        for suit in range(4):
            for face in range(1, 14):
                cards.append(Card(face, Suit.suits[suit]))
        random.shuffle(cards)
    def hit(self):
        return cards.pop()

class Board:
    """Board/Community cards"""
    def __init__(self):
        self.cards = []
    def add(self, card):
        cards.append(card)
    def __str__(self):
        ret = "  "
        for c in cards:
            ret = "%s %s" % (ret, c)
        return ret

class Status:
    PREFLOP = "preflop"
    FLOP    = "flop"
    TURN    = "turn"
    RIVER   = "river"

    def __init__(self, status):
        self.status = status
    def is_in_preflop(self):
        return status == PREFLOP
    def is_in_flop(self):
        return status == FLOP
    def is_in_turn(self):
        return status == TURN
    def is_in_river(self):
        return status == RIVER

class Game:
    """Texas hold'em game"""

    class Status:
        """Status of the game"""
        PREFLOP = "preflop"
        FLOP    = "flop"
        TURN    = "turn"
        RIVER   = "river"
    def __init__(self):
        self.deck = Deck()
        self.board = Board()
        self.hand = Hand(self.deck.hit(), self.deck.hit())
        self.status = Status.PREFLOP
        self.last_card = None
    def __burn__(self):
        """Take a card from the Deck and burn it (dont show)"""
        deck.hit()
    def __hit__(self):
        """Take a card from the Deck and add it to the Board"""
        last_card = deck.hit()
        board.add(last_card)
    def flop(self):
        """Burn one and hit three"""
        __burn__()
        for i in range(3):
            __hit__()
        status = Status.FLOP
    def turn(self):
        """Burn one and hit one"""
        __burn__()
        __hit__()
        status = Status.TURN
    def river(self):
        """Burn one and hit one"""
        __burn__()
        __hit__()
        status = Status.RIVER
    def is_in_preflop(self):
        return status == Status.PREFLOP
    def is_in_flop(self):
        return status == Status.FLOP
    def is_in_turn(self):
        return status == Status.TURN
    def is_in_river(self):
        return status == Status.RIVER
    def __str__(self):
        return "%s %s" % (hand, board)

