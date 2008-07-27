package org.b1n.cheater;

import java.awt.AWTException;
import java.awt.Robot;
import java.util.List;

public class InventoryDropCheater extends AbstractInvetoryCheater {

    // Offsets para item de menu para drop de item (depois de clicado com botao direito do mouse)
    private static final int DROP_X_OFFSET = 0;

    private static final int DROP_Y_OFFSET = 40;

    private static final int DROP_LAST_ROW_X_OFFSET = 0;

    private static final int DROP_LAST_ROW_Y_OFFSET = 20;

    public InventoryDropCheater(int iniX, int iniY, int... slotsToIgnore) {
        super(iniX, iniY, slotsToIgnore);
    }

    @Override
    protected void addActions(Slot slot, List<MouseAction> actions) {
        // Right click para abrir menu para dropar item
        actions.add(new MouseAction("SLOT " + slot + " RIGHT", slot.x, slot.y, MouseButton.RIGHT, 0));

        // Dropa item somando offset de drop (se for a ultima linha o offset eh diferente)
        actions.add(getDropAction(slot));
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
        return new MouseAction("SLOT " + slot + " LEFT", dropX, dropY, MouseButton.LEFT, 100);
    }

    @Override
    protected Robot getRobotToUse() throws AWTException {
        return new Robot();
    }

    public static void main(String[] args) {
        new InventoryDropCheater(592, 409, 0, 1, 2, 3).cheat();
    }
}
