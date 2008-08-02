package org.b1n.cheater;

import java.awt.AWTException;
import java.awt.Robot;

/**
 * Teleport to Camelot (raise Magic XP).
 * @author Marcio Ribeiro (mmr)
 * @created Aug 2, 2008
 */
public class CamelotTransportCheater extends AbstractActionListCheater {

    private int x;

    private int y;

    /**
     * Construtor.
     * @param x tele button x coord.
     * @param y tele button y coord.
     */
    public CamelotTransportCheater(int x, int y) {
        this.x = x;
        this.y = y;
    }

    /**
     * @return tele camelot action.
     */
    @Override
    protected MouseAction getMouseAction() {
        // Click the teletransport button
        return new MouseClick("Tele Camelot", x, y, MouseButton.LEFT, 1500);
    }

    /**
     * @return awt robot.
     */
    @Override
    protected Robot getRobotToUse() throws AWTException {
        return new FakeRobot();
    }

    /**
     * Run cheater.
     * @param args args.
     */
    public static void main(String[] args) {
        new CamelotTransportCheater(100, 200).cheat();
    }
}
