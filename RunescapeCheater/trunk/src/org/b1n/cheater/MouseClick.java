package org.b1n.cheater;

import java.awt.Robot;

/**
 * Mouse click.
 * @author Marcio Ribeiro (mmr)
 * @created Aug 2, 2008
 */
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

    /**
     * Construtor.
     * @param name name.
     * @param x x coord.
     * @param y y coord.
     * @param button button.
     */
    public MouseClick(String name, int x, int y, MouseButton button) {
        this(name, x, y, button, DEFAULT_DELAY);
    }

    /**
     * Construtor.
     * @param x x coord.
     * @param y y coord.
     * @param button mouse button.
     * @param delayAfter delay after click (in ms).
     */
    public MouseClick(int x, int y, MouseButton button, int delayAfter) {
        this(null, x, y, button, delayAfter, DEFAULT_X_VAR, DEFAULT_Y_VAR);
    }

    /**
     * Construtor.
     * @param name name.
     * @param x x coord.
     * @param y y coord.
     * @param button mouse button.
     * @param delayAfter delay after click (in ms).
     */
    public MouseClick(String name, int x, int y, MouseButton button, int delayAfter) {
        this(name, x, y, button, delayAfter, DEFAULT_X_VAR, DEFAULT_Y_VAR);
    }

    /**
     * Construtor.
     * @param name name.
     * @param x x coord.
     * @param y y coord.
     * @param button mouse button.
     * @param delayAfter delay after click (in ms).
     * @param xVar x coord possible variation.
     * @param yVar y coord possible variation.
     */
    public MouseClick(String name, int x, int y, MouseButton button, int delayAfter, int xVar, int yVar) {
        this.name = name;
        this.x = x;
        this.y = y;
        this.button = button;
        this.delayAfter = delayAfter;
        this.xVar = xVar;
        this.yVar = yVar;
    }

    /**
     * @return name.
     */
    public String getName() {
        return this.name;
    }

    /**
     * @return bot.
     */
    private Robot getBot() {
        if (robot == null) {
            robot = MouseActionConfig.getInstance().getRobot();
        }
        return robot;
    }

    /**
     * Click!
     */
    public void run() {
        int rX = x;
        int rY = y;
        int rDelay = (int) (delayAfter * ((Math.random() * 3 / 100) + 1));
        if ((int) (Math.random() * 2) == 1) {
            rX += xVar + (int) (Math.random() * 2) == 1 ? 1 : -1;
            rY += yVar + (int) (Math.random() * 2) == 1 ? 1 : -1;
        }
        if (name == null) {
            System.out.println(button + " click at " + x + ", " + y);
        } else {
            System.out.println(name);
        }
        getBot().mouseMove(rX, rY);
        getBot().mousePress(button.getMask());
        getBot().mouseRelease(button.getMask());
        getBot().delay(rDelay);
    }

    /**
     * @return name.
     */
    @Override
    public String toString() {
        return name;
    }
}
