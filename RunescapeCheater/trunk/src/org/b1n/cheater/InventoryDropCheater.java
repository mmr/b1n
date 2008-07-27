package org.b1n.cheater;

import java.awt.AWTException;
import java.awt.Robot;

public class InventoryDropCheater extends AbstractInvetoryCheater {
    private static final String MAIN_GROUP_NAME = "Drop Dead!";

    // Offsets para item de menu para drop de item (depois de clicado com botao direito do mouse)
    private static final int DROP_X_OFFSET = 0;

    private static final int DROP_Y_OFFSET = 40;

    private static final int DROP_LAST_ROW_X_OFFSET = 0;

    private static final int DROP_LAST_ROW_Y_OFFSET = 20;

    public InventoryDropCheater(int iniX, int iniY, int... slotsToIgnore) {
        super(MAIN_GROUP_NAME, iniX, iniY, slotsToIgnore);
    }

    @Override
    protected MouseAction getMouseActionForSlot(Slot slot) {
        MouseActionGroup dropItem = new MouseActionGroup("Drop item in slot " + slot);

        // Right click para abrir menu para dropar item
        dropItem.add(new MouseClick(slot.x, slot.y, MouseButton.RIGHT, 0));

        // Dropa item somando offset de drop (se for a ultima linha o offset eh diferente)
        dropItem.add(getDropAction(slot));

        return dropItem;
    }

    private MouseAction getDropAction(Slot slot) {
        int dropX = slot.x;
        int dropY = slot.y;
        if (slot.isInLastRow()) {
            dropX += DROP_LAST_ROW_X_OFFSET;
            dropY += DROP_LAST_ROW_Y_OFFSET;
        } else {
            dropX += DROP_X_OFFSET;
            dropY += DROP_Y_OFFSET;
        }
        return new MouseClick(dropX, dropY, MouseButton.LEFT, 100);
    }

    @Override
    protected Robot getRobotToUse() throws AWTException {
        return new Robot();
    }

    public static void main(String[] args) {
        new InventoryDropCheater(590, 378, 0, 1, 2).cheat();
    }
}
