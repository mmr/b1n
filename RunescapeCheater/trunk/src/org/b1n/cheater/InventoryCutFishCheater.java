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
        knifeSlot = findSlotByName(KNIFE_SLOT);
    }

    @Override
    protected MouseAction getMouseActionForSlot(Slot slot) {
        MouseActionGroup cutFish = new MouseActionGroup("Cut fish in slot " + slot);

        // Left click knife
        cutFish.add(new MouseClick(knifeSlot.x, knifeSlot.y, MouseButton.LEFT, 0));

        // Left click fish
        cutFish.add(new MouseClick(slot.x, slot.y, MouseButton.LEFT, 1000));

        return cutFish;
    }

    @Override
    protected Robot getRobotToUse() throws AWTException {
        return new Robot();
    }

    public static void main(String[] args) {
        new InventoryCutFishCheater(592, 380, 0, 1, 2, 3).cheat();
        // new InventoryCutFishCheater(592, 380, 0, 1, 2, 3, 3, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27).cheat();
    }
}
