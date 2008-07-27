package org.b1n.cheater;

import java.awt.AWTException;
import java.awt.Robot;

public class InventoryCutFishCheater extends AbstractInvetoryCheater {
    private static final String MAIN_GROUP_NAME = "Cut Fish!";

    // Knife slot
    private static final int KNIFE_SLOT = 0;

    private Slot knifeSlot;

    public InventoryCutFishCheater(int iniX, int iniY, int... slotsToIgnore) {
        super(MAIN_GROUP_NAME, iniX, iniY, slotsToIgnore);
        MouseActionConfig.getInstance().setAutoDelay(500);
        knifeSlot = findSlotByName(KNIFE_SLOT);
    }

    @Override
    protected MouseAction getMouseActionForSlot(Slot slot) {
        MouseActionGroup cutFish = new MouseActionGroup("Cut fish in slot " + slot);

        // Left click knife
        cutFish.add(new MouseClick("SLOT " + slot + " KNIFE CLICK", knifeSlot.x, knifeSlot.y, MouseButton.LEFT, 0));

        // Left click fish
        cutFish.add(new MouseClick("SLOT " + slot + " FISH CLICK", slot.x, slot.y, MouseButton.LEFT, 0));

        return cutFish;
    }

    @Override
    protected Robot getRobotToUse() throws AWTException {
        return new FakeRobot();
    }

    public static void main(String[] args) {
        new InventoryCutFishCheater(592, 409, 0, 1, 2, 3).cheat();
    }
}
