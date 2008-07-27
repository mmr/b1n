package org.b1n.cheater;

import java.awt.AWTException;
import java.awt.Robot;
import java.util.ArrayList;
import java.util.HashSet;
import java.util.List;
import java.util.Set;

public class InventoryDropCheater extends AbstractActionListCheater {

    private int iniX;

    private int iniY;

    // Numero de linhas do inventario
    // private static final int ROWS = 7;

    private static final int ROWS = 7;

    // Numero de colunas no inventario
    // private static final int COLS = 4;

    private static final int COLS = 4;

    // Offsets para item de menu para drop de item (depois de clicado com botao direito do mouse)
    private static final int DROP_X_OFFSET = 0;

    private static final int DROP_Y_OFFSET = 40;

    private static final int DROP_LAST_ROW_X_OFFSET = 0;

    private static final int DROP_LAST_ROW_Y_OFFSET = 20;

    // Offsets para item no inventario (distancia entre centros)
    private static final int INV_X_OFFSET = 42;

    private static final int INV_Y_OFFSET = 36;

    // Set de itens que nao serao dropados (0 a 27, comecando do item do canto esquerdo superior)
    private Set<Integer> dontDropSet;

    /**
     * Construtor.
     * @param iniX x do centro do primeiro slot (top left).
     * @param iniY y do centro do primeiro slot (top left).
     */
    public InventoryDropCheater(int iniX, int iniY, int... d) {
        MouseActionConfig.getInstance().setAutoDelay(150);
        
        this.iniX = iniX;
        this.iniY = iniY;
        dontDropSet = new HashSet<Integer>();
        for (int i : d) {
            dontDropSet.add(i);
        }
    }

    @Override
    protected List<MouseAction> getActions() {
        // final Slot[][] slots ={
        // { new Slot(0, 0), new Slot(42, 0), new Slot(84, 0), new Slot(126, 0) },
        // { new Slot(0, 36), new Slot(42, 36), new Slot(84, 36), new Slot(126, 36) },
        // { new Slot(0, 72), new Slot(42, 72), new Slot(84, 72), new Slot(126, 72) },
        // { new Slot(0, 108), new Slot(42, 108), new Slot(84, 108), new Slot(126, 108) },
        // { new Slot(0, 144), new Slot(42, 144), new Slot(84, 144), new Slot(126, 144) },
        // { new Slot(0, 180), new Slot(42, 180), new Slot(84, 180), new Slot(126, 180) },
        // { new Slot(0, 216), new Slot(42, 216), new Slot(84, 216), new Slot(126, 216) },
        // };

        final List<Slot> slots = new ArrayList<Slot>();
        int cx = -1;
        for (int i = 0; i < ROWS; i++) {
            for (int j = 0; j < COLS; j++) {
                cx++;
                if (dontDropSet.contains(cx)) {
                    System.out.println("IGNORING SLOT " + cx + " [" + j + ", " + i + "]");
                    continue;
                }
                slots.add(new Slot(cx, j, i));
            }
        }

        final List<MouseAction> actions = new ArrayList<MouseAction>();
        for (Slot slot : slots) {
            // Right click para abrir menu para dropar item
            actions.add(new MouseAction("SLOT " + slot + " RIGHT", slot.x, slot.y, MouseButton.RIGHT, 0));

            // Dropa item somando offset de drop (se for a ultima linha o offset eh diferente)
            actions.add(getDropAction(slot));
        }
        return actions;
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
        return new MouseAction("SLOT " + slot + " LEFT", dropX, dropY, MouseButton.LEFT, 0);
    }

    private class Slot {
        int n;

        int x;

        int y;

        Slot(int n, int j, int i) {
            this.n = n;
            this.x = iniX + INV_X_OFFSET * j;
            this.y = iniY + INV_Y_OFFSET * i;
        }

        boolean isInLastRow() {
            return y == iniY + INV_Y_OFFSET * (ROWS - 1);
        }

        @Override
        public String toString() {
            return n + " (" + x + ", " + y + ")";
        }
    }

    @Override
    protected int getNumberOfTakes() {
        return 1;
    }

    @Override
    protected int getStartDelay() {
        return 2000;
    }

    @Override
    protected Robot getRobotToUse() throws AWTException {
        return new Robot();
    }

    public static void main(String[] args) {
        new InventoryDropCheater(592, 409, 0, 1, 2, 3).cheat();
    }
}
