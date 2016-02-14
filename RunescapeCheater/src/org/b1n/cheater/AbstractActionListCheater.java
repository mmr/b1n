package org.b1n.cheater;

import java.awt.AWTException;
import java.awt.Robot;

/**
 * Mouse action list based cheater.
 * @author Marcio Ribeiro (mmr)
 * @created Aug 2, 2008
 */
public abstract class AbstractActionListCheater implements Cheater {

    /** Default delay (in ms) before start. */
    private static final int DEFAULT_START_DELAY = 3000;

    /** Default number of runs. */
    private static final int DEFAULT_NUMBER_OF_RUNS = 1;

    /**
     * @return Fake Robot.
     * @throws AWTException awt exception.
     */
    protected Robot getRobotToUse() throws AWTException {
        return new FakeRobot();
    }

    /**
     * Cheat!
     */
    public void cheat() {
        prepare();

        System.out.println("Go go go!");
        for (int i = 0; i < getNumberOfTakes(); i++) {
            getMouseAction().run();
        }
    }

    /**
     * Prepare robot before cheat starts.
     */
    private void prepare() {
        try {
            MouseActionConfig.getInstance().setRobot(getRobotToUse());
        } catch (AWTException e) {
            throw new IllegalStateException();
        }

        if (getStartDelay() > 0) {
            System.out.println("Hold on...");
            try {
                Thread.sleep(getStartDelay());
            } catch (InterruptedException e) {
                e.printStackTrace();
            }
        }
    }

    /**
     * @return number of test runs (takes).
     */
    protected int getNumberOfTakes() {
        return DEFAULT_NUMBER_OF_RUNS;
    }

    /**
     * @return delay (in ms) before start.
     */
    protected int getStartDelay() {
        return DEFAULT_START_DELAY;
    }

    /**
     * @return mouse action to be run in test.
     */
    protected abstract MouseAction getMouseAction();
}
