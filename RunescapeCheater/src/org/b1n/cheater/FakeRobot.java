package org.b1n.cheater;

import java.awt.AWTException;
import java.awt.Robot;

/**
 * Fake robot for testing purposes.
 * @author Marcio Ribeiro (mmr)
 * @created Aug 2, 2008
 */
class FakeRobot extends Robot {

    /**
     * Construtor.
     * @throws AWTException awt exception.
     */
    public FakeRobot() throws AWTException {
        super();
    }

    /**
     * Log message.
     * @param msg msg.
     */
    private void log(String msg) {
        // System.out.println(msg);
    }

    /**
     * Delay.
     * @param ms ms.
     */
    @Override
    public synchronized void delay(int ms) {
        log("DELAY: " + ms);
    }

    /**
     * Move mouse.
     * @param x x.
     * @param y y.
     */
    @Override
    public synchronized void mouseMove(int x, int y) {
        log("MOVE:  " + x + ", " + y);
    }

    /**
     * Click.
     * @param buttons buttons.
     */
    @Override
    public synchronized void mousePress(int buttons) {
        log("CLICK: " + MouseButton.getByMask(buttons));
    }

    /**
     * Release.
     * @param buttons buttons.
     */
    @Override
    public synchronized void mouseRelease(int buttons) {
        // nothing
    }
}
