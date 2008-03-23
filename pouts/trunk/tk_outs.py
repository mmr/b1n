#!/usr/bin/env python
from Tkinter import *

class App:
    def __init__(self, master):
        frame = Frame(master)
        frame.pack()

        self.hand_label = Label(frame, text="Hand")
        self.hand_label.pack(side=LEFT)

        self.hand = Entry(frame)
        self.hand.pack(side=LEFT)

        self.show_outs = Button(frame, text="Show Outs", command=self.calc_outs)
        self.show_outs.pack(side=LEFT)

        self.quit = Button(frame, text="Quit", fg="red", command=frame.quit)
        self.quit.pack()

    def calc_outs(self):
        print self.hand.get()

root = Tk()
app = App(root)
root.mainloop()
