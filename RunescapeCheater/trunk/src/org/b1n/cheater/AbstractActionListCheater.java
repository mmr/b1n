package org.b1n.cheater;

import java.awt.AWTException;
import java.awt.Robot;
import java.util.List;

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
            System.out.println("\n============\n> TAKE " + i);
            for (MouseAction action : getActions()) {
                System.out.println(">> ACTION " + action.getName());
                action.run();
            }
            // try {
            // Thread.sleep(2000);
            // } catch (InterruptedException e) {
            // e.printStackTrace();
            // }
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

    protected abstract List<MouseAction> getActions();
}
