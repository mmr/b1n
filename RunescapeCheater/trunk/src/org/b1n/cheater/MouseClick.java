package org.b1n.cheater;

import java.awt.Robot;

public class MouseClick implements MouseAction {

    private String name;

    private int x;

    private int y;

    private MouseButton button;

    private int delayAfter;

    private int xVar;

    private int yVar;

    private Robot robot;

    private static final int DEFAULT_DELAY = 500;

    private static final int DEFAULT_X_VAR = 1;

    private static final int DEFAULT_Y_VAR = 1;

    public MouseClick(String name, int x, int y, MouseButton button) {
        this(name, x, y, button, DEFAULT_DELAY);
    }

    public MouseClick(String name, int x, int y, MouseButton button, int delayAfter) {
        this(name, x, y, button, delayAfter, DEFAULT_X_VAR, DEFAULT_Y_VAR);
    }

    public MouseClick(String name, int x, int y, MouseButton button, int delayAfter, int xVar, int yVar) {
        this.name = name;
        this.x = x;
        this.y = y;
        this.button = button;
        this.delayAfter = delayAfter;
        this.xVar = xVar;
        this.yVar = yVar;
    }

    public String getName() {
        return this.name;
    }

    private Robot getBot() {
        if (robot == null) {
            robot = MouseActionConfig.getInstance().getRobot();
        }
        return robot;
    }

    public void run() {
        int rX = x;
        int rY = y;
        int rDelay = (int) (delayAfter * ((Math.random() * 3 / 100) + 1));
        if ((int) (Math.random() * 2) == 1) {
            rX += xVar + (int) (Math.random() * 2) == 1 ? 1 : -1;
            rY += yVar + (int) (Math.random() * 2) == 1 ? 1 : -1;
        }
        getBot().mouseMove(rX, rY);
        getBot().mousePress(button.getMask());
        getBot().mouseRelease(button.getMask());
        getBot().delay(rDelay);
    }

    @Override
    public String toString() {
        return name;
    }
}
