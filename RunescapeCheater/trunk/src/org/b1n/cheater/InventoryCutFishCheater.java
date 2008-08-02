package org.b1n.cheater;

import java.awt.AWTException;
import java.awt.Robot;

/**
 * Cut fish (barbarian fishing).
 * @author Marcio Ribeiro (mmr)
 * @created Aug 2, 2008
 */
public class InventoryCutFishCheater extends AbstractInvetoryCheater {
    private static final String MAIN_GROUP_NAME = "Cut Fish!";

    // Knife slot
    private static final int KNIFE_SLOT = 0;

    private Slot knifeSlot;

    /**
     * Construtor.
     * @param iniX slot 0 x.
     * @param iniY slot 0 y.
     * @param slotsToIgnore
     */
    public InventoryCutFishCheater(int iniX, int iniY, int... slotsToIgnore) {
        super(MAIN_GROUP_NAME, iniX, iniY, slotsToIgnore);
        knifeSlot = findSlotByName(KNIFE_SLOT);
    }

    /**
     * @param slot.
     * @return mouse action for the given slot.
     */
    @Override
    protected MouseAction getMouseActionForSlot(Slot slot) {
        MouseActionGroup cutFish = new MouseActionGroup("Cut fish in slot " + slot);

        // Left click knife
        cutFish.add(new MouseClick(knifeSlot.x, knifeSlot.y, MouseButton.LEFT, 0));

        // Left click fish
        cutFish.add(new MouseClick(slot.x, slot.y, MouseButton.LEFT, 1000));

        return cutFish;
    }

    /**
     * @return awt robot.
     * @throws AWTException awt exception.
     */
    @Override
    protected Robot getRobotToUse() throws AWTException {
        return new Robot();
    }

    /**
     * Cheat!
     * @param args args.
     */
    public static void main(String[] args) {
        new InventoryCutFishCheater(592, 380, 0, 1, 2, 3).cheat();
    }
}
