#!/usr/bin/env python

#-*- coding: iso-8859-1 -*-

__author__ = 'Marcio Ribeiro <mmr@b1n.org>'
__license__ = 'BSD License'
__copyright__ = 'Copyright 2008 Marcio Ribeiro'

import sys
from calculator import *
from game import *
from PyQt4.QtCore import *
from PyQt4.QtGui import *

#Suit.suits = [
#    Suit("<font color=#000>&spades;</font>"),
#    Suit("<font color=#f00>&hearts;</font>"),
#    Suit("<font color=#f00>&diams;</font>"),
#    Suit("<font color=#000>&clubs;</font>")]

#Suit.suits = [
#    Suit("<font color=#000>&spades;</font>"),
#    Suit("<font color=#f00>&hearts;</font>"),
#    Suit("<font color=#f00>&diams;</font>"),
#    Suit("<font color=#000>&clubs;</font>")]

class Form(QDialog):
    def __init__(self,parent=None):
        super(Form, self).__init__(parent)

        self.game = Game()
        self.browser = QTextBrowser()
        self.lineedit = QLineEdit("Outs")
        self.lineedit.selectAll()
        layout = QVBoxLayout()
        layout.addWidget(self.browser)
        layout.addWidget(self.lineedit)
        self.setLayout(layout)
        self.lineedit.setFocus()
        self.connect(self.lineedit, SIGNAL("returnPressed()"), self.updateUi)
        self.setWindowTitle("Pouts")
        self.updateUi()

    def updateUi(self):
        def w(b, o):
            #import re
            #s = {
            #    re.compile("([0-9TJQKA])s"): "\\1&spades;",
            #    re.compile("([0-9TJQKA])h"): "\\1&hearts;",
            #    re.compile("([0-9TJQKA])d"): "\\1&dims;",
            #    re.compile("([0-9TJQKA])c"): "\\1&clubs;"
            #}
            #for k, v in s.iteritems():
            #    t = k.sub(v, t.__str__())
            #b.setHtml(unicode(t))
            t = b.toPlainText() + o.__str__()
            b.setText(t)

        if self.game.is_in_preflop():
            w(self.browser, self.game.hand)
            self.game.flop()
            w(self.browser, self.game.board)
            return
        elif self.game.is_in_flop():
            self.game.turn()
            w(self.browser, " ")
            w(self.browser, self.game.last_card)
        elif self.game.is_in_turn():
            self.game.river()
            w(self.browser, " ")
            w(self.browser, self.game.last_card)
        else:
            self.game = Game()
            self.browser.clear()
            self.updateUi()

def main(args):
    app = QApplication(args)
    form = Form()
    form.show()
    app.exec_()

if __name__ == "__main__":
    main(sys.argv)

