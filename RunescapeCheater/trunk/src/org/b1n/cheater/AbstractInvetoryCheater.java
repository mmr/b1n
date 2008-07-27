package org.b1n.cheater;

import java.util.ArrayList;
import java.util.HashSet;
import java.util.List;
import java.util.Set;

public abstract class AbstractInvetoryCheater extends AbstractActionListCheater {

    protected int iniX;

    protected int iniY;

    protected Set<Integer> ignoredSlots;

    // Numero de linhas do inventario
    private static final int ROWS = 7;

    // Numero de colunas no inventario
    private static final int COLS = 4;

    // Offsets para item no inventario (distancia entre centros)
    private static final int INV_X_OFFSET = 42;

    private static final int INV_Y_OFFSET = 36;

    /**
     * Construtor.
     * @param iniX x do centro do primeiro slot (top left).
     * @param iniY y do centro do primeiro slot (top left).
     * @param slotsToIgnore slots a serem ignorados.
     */
    public AbstractInvetoryCheater(int iniX, int iniY, int... slotsToIgnore) {
        MouseActionConfig.getInstance().setAutoDelay(100);
        this.iniX = iniX;
        this.iniY = iniY;
        this.ignoredSlots = new HashSet<Integer>();
        for (int i : slotsToIgnore) {
            ignoredSlots.add(i);
        }
    }

    @Override
    protected List<MouseAction> getActions() {
        final List<Slot> slots = new ArrayList<Slot>();
        int cx = 0;
        for (int i = 0; i < ROWS; i++) {
            for (int j = 0; j < COLS; j++) {
                slots.add(new Slot(cx++, j, i));
            }
        }

        final List<MouseAction> actions = new ArrayList<MouseAction>();
        for (Slot slot : slots) {
            if (!ignoreSlot(slot)) {
                addActions(slot, actions);
            }
        }
        return actions;
    }

    protected boolean ignoreSlot(Slot slot) {
        return ignoredSlots.contains(slot.n);
    }

    protected abstract void addActions(Slot slot, List<MouseAction> actions);

    class Slot {
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
}
