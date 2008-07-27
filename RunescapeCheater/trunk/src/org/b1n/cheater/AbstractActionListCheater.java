package org.b1n.cheater;

import java.awt.AWTException;
import java.awt.Robot;

public abstract class AbstractActionListCheater implements Cheater {

    private static final int DEFAULT_START_DELAY = 3000;

    private static final int DEFAULT_NUMBER_OF_RUNS = 50;

    protected Robot getRobotToUse() throws AWTException {
        return new FakeRobot();
    }

    public void cheat() {
        prepare();

        System.out.println("Go go go!");
        for (int i = 0; i < getNumberOfTakes(); i++) {
            getMouseAction().run();
        }
    }

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

    protected int getNumberOfTakes() {
        return DEFAULT_NUMBER_OF_RUNS;
    }

    protected int getStartDelay() {
        return DEFAULT_START_DELAY;
    }

    protected abstract MouseAction getMouseAction();
}
