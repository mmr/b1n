package org.b1n.cheater;

import java.util.ArrayList;
import java.util.List;

public class TreasureCheater extends AbstractActionListCheater {

    @Override
    protected List<MouseAction> getActions() {
        return new ArrayList<MouseAction>() {
            {
                add(new MouseAction("LEFT CASKET RIGHT BUTTON", 190, 333, MouseButton.RIGHT, 200));
                add(new MouseAction("LEFT CASKET LEFT BUTTON", 164, 373, MouseButton.LEFT, 5000));

                add(new MouseAction("RIGHT CASKET RIGHT BUTTON", 300, 333, MouseButton.RIGHT, 200));
                add(new MouseAction("RIGHT CASKET LEFT BUTTON", 275, 375, MouseButton.LEFT, 7000));
            }
        };
    }

    public static void main(String[] args) {
        Cheater cheater = new TreasureCheater();
        cheater.cheat();
    }
}
