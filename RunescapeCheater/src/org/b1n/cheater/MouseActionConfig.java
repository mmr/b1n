package org.b1n.cheater;

import java.awt.Robot;

/**
 * Configuration of the action.
 * @author Marcio Ribeiro (mmr)
 * @created Aug 2, 2008
 */
public class MouseActionConfig {
    private static MouseActionConfig INSTANCE = new MouseActionConfig();

    private static final int DEFAULT_AUTO_DELAY = 500;

    private Robot robot;

    private Integer autoDelay;

    /**
     * Construtor.
     */
    private MouseActionConfig() {
        // do nothing
    }

    /**
     * @return the sole instance of the configuration.
     */
    public static MouseActionConfig getInstance() {
        return INSTANCE;
    }

    /**
     * @param robot robot to use.
     */
    public void setRobot(Robot robot) {
        robot.setAutoDelay(getAutoDelay());
        this.robot = robot;
    }

    /**
     * @return auto delay to use in the robot.
     */
    public int getAutoDelay() {
        if (autoDelay == null) {
            return DEFAULT_AUTO_DELAY;
        }
        return autoDelay;
    }

    /**
     * Defines the autodelay.
     * @param autoDelay auto delay.
     */
    public void setAutoDelay(int autoDelay) {
        this.autoDelay = autoDelay;
    }

    /**
     * @return robot.
     */
    public Robot getRobot() {
        if (robot == null) {
            throw new IllegalStateException();
        }
        return robot;
    }

}
