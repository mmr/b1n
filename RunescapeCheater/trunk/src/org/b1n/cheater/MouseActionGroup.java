package org.b1n.cheater;

import java.util.ArrayList;
import java.util.List;

public class MouseActionGroup implements MouseAction {
    private List<MouseAction> actions = new ArrayList<MouseAction>();

    private String name;

    public MouseActionGroup(String name, MouseAction... actionsToAdd) {
        this.name = name;
        addActions(actionsToAdd);
    }

    public void add(MouseAction... actionsToAdd) {
        addActions(actionsToAdd);
    }

    private void addActions(MouseAction... actionsToAdd) {
        for (MouseAction mouseAction : actionsToAdd) {
            this.actions.add(mouseAction);
        }
    }

    public void run() {
        System.out.println("GROUP : " + name);
        for (MouseAction action : actions) {
            action.run();
        }
    }

    public String getName() {
        return name;
    }

    @Override
    public String toString() {
        return name;
    }
}
