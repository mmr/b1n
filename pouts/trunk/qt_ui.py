#!/usr/bin/env python

__author__ = 'Marcio Ribeiro <mmr@b1n.org>'
__license__ = 'BSD License'
__copyright__ = 'Copyright 2008 Marcio Ribeiro'

import sys
from calculator import *
from game import *
from PyQt4.QtCore import *
from PyQt4.QtGui import *

Suit.suits = [
    Suit("<font color=#000>&spades;</font>"),
    Suit("<font color=#f00>&hearts;</font>"),
    Suit("<font color=#f00>&diams;</font>"),
    Suit("<font color=#000>&clubs;</font>")]

class Form(QDialog):
    def __init__(self,parent=None):
        super(Form, self).__init__(parent)

        self.browser = QTextBrowser()
        self.game = Game()
        self.browser.append(unicode(self.game.hand))
        self.updateUi()
        self.lineedit = QLineEdit("Outs")
        self.lineedit.selectAll()
        layout = QVBoxLayout()
        layout.addWidget(self.browser)
        layout.addWidget(self.lineedit)
        self.setLayout(layout)
        self.lineedit.setFocus()
        self.connect(self.lineedit, SIGNAL("returnPressed()"), self.updateUi)
        self.setWindowTitle("Pouts")

    def updateUi(self):
        self.browser.append(unicode(self.game.board))
        if self.game.is_in_flop():
            self.game.turn()
        elif self.game.is_in_turn():
            self.game.river()
        elif self.game.is_in_river():
            self.game = Game()
            self.browser.append(unicode("<b>....................</b>"))
            self.browser.append(unicode(self.game.hand))

def main(args):
    app = QApplication(args)
    form = Form()
    form.show()
    app.exec_()

if __name__ == "__main__":
    main(sys.argv)

