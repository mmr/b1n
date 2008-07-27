package org.b1n.cheater;

import java.awt.Robot;

public class MouseActionConfig {
    private static MouseActionConfig INSTANCE = new MouseActionConfig();

    private static final int DEFAULT_AUTO_DELAY = 500;

    private Robot robot;

    private Integer autoDelay;

    private MouseActionConfig() {
        // do nothing
    }

    public static MouseActionConfig getInstance() {
        return INSTANCE;
    }

    public void setRobot(Robot robot) {
        robot.setAutoDelay(getAutoDelay());
        this.robot = robot;
    }

    public int getAutoDelay() {
        if (autoDelay == null) {
            return DEFAULT_AUTO_DELAY;
        }
        return autoDelay;
    }

    public void setAutoDelay(int autoDelay) {
        this.autoDelay = autoDelay;
    }

    public Robot getRobot() {
        if (robot == null) {
            throw new IllegalStateException();
        }
        return robot;
    }

}
