package org.b1n.cheater;

import java.awt.AWTException;
import java.awt.Robot;

class FakeRobot extends Robot {

    public FakeRobot() throws AWTException {
        super();
    }

    void log(String msg) {
        System.out.println(msg);
    }

    @Override
    public synchronized void delay(int ms) {
        log("DELAY: " + ms);
    }

    @Override
    public synchronized void mouseMove(int x, int y) {
        log("MOVE:  " + x + ", " + y);
    }

    @Override
    public synchronized void mousePress(int buttons) {
        log("CLICK: " + MouseButton.getByMask(buttons));
    }

    @Override
    public synchronized void mouseRelease(int buttons) {
        // nothing
    }
}
