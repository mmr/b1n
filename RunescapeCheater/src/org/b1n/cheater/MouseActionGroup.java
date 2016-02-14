package org.b1n.cheater;

import java.util.ArrayList;
import java.util.List;

/**
 * Mouse action composite.
 * @author Marcio Ribeiro (mmr)
 * @created Aug 2, 2008
 */
public class MouseActionGroup implements MouseAction {
    private List<MouseAction> actions = new ArrayList<MouseAction>();

    private String name;

    private static int cx;

    /**
     * Construtor.
     * @param name name of the action.
     * @param actionsToAdd actions to add.
     */
    public MouseActionGroup(String name, MouseAction... actionsToAdd) {
        this.name = name;
        add(actionsToAdd);
    }

    /**
     * Add action to this composite.
     * @param actionsToAdd actions to add.
     */
    public void add(MouseAction... actionsToAdd) {
        for (MouseAction mouseAction : actionsToAdd) {
            this.actions.add(mouseAction);
        }
    }

    /**
     * Run!
     */
    public void run() {
        System.out.println(name);
        for (MouseAction action : actions) {
            cx++;
            indent();
            action.run();
            cx--;
        }
    }

    /**
     * Indent.
     */
    private void indent() {
        for (int i = 0; i < cx; i++) {
            System.out.print(">");
        }
        System.out.print(" ");
    }

    /**
     * @return name of the action.
     */
    public String getName() {
        return name;
    }

    /**
     * @return description.
     */
    @Override
    public String toString() {
        return name;
    }
}
