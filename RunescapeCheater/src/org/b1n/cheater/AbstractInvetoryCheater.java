package org.b1n.cheater;

import java.util.ArrayList;
import java.util.HashSet;
import java.util.List;
import java.util.Set;

/**
 * Inventory mouse action based cheater.
 * @author Marcio Ribeiro (mmr)
 * @created Aug 2, 2008
 */
public abstract class AbstractInvetoryCheater extends AbstractActionListCheater {

    protected int iniX;

    protected int iniY;

    protected List<Slot> slots;

    protected Set<Integer> ignoredSlots;

    // Numero de linhas do inventario
    private static final int ROWS = 7;

    // Numero de colunas no inventario
    private static final int COLS = 4;

    // Offsets para item no inventario (distancia entre centros)
    private static final int INV_X_OFFSET = 42;

    private static final int INV_Y_OFFSET = 36;

    private String mainGroupName;

    /**
     * Construtor.
     * @param mainGroupName nome do grupo principal.
     * @param iniX x do centro do primeiro slot (top left).
     * @param iniY y do centro do primeiro slot (top left).
     * @param slotsToIgnore slots a serem ignorados.
     */
    public AbstractInvetoryCheater(String mainGroupName, int iniX, int iniY, int... slotsToIgnore) {
        MouseActionConfig.getInstance().setAutoDelay(100);
        this.mainGroupName = mainGroupName;
        this.iniX = iniX;
        this.iniY = iniY;
        configureIgnoredSlots(slotsToIgnore);
        configureSlots();
    }

    /**
     * Configure ignored slots.
     * @param slotsToIgnore slots to ignore.
     */
    private void configureIgnoredSlots(int... slotsToIgnore) {
        this.ignoredSlots = new HashSet<Integer>();
        for (int i : slotsToIgnore) {
            ignoredSlots.add(i);
        }
    }

    /**
     * Configure slots.
     */
    private void configureSlots() {
        this.slots = new ArrayList<Slot>();
        int cx = 0;
        for (int i = 0; i < ROWS; i++) {
            for (int j = 0; j < COLS; j++) {
                slots.add(new Slot(cx++, j, i));
            }
        }
    }

    /**
     * @return action to run.
     */
    @Override
    protected final MouseAction getMouseAction() {
        MouseActionGroup mainGroup = new MouseActionGroup(mainGroupName);
        for (Slot slot : slots) {
            if (!shouldIgnoreSlot(slot)) {
                mainGroup.add(getMouseActionForSlot(slot));
            }
        }
        return mainGroup;
    }

    /**
     * Find a slot with the given name.
     * @param n name of the slot.
     * @return slot with the passed name.
     */
    protected final Slot findSlotByName(int n) {
        for (Slot slot : slots) {
            if (slot.n == n) {
                return slot;
            }
        }
        throw new IllegalStateException("Slot not found : " + n);
    }

    /**
     * @return slots.
     */
    protected final List<Slot> getSlots() {
        return slots;
    }

    /**
     * @param slot slot to be checked.
     * @return <code>true</code> if the slot should be ignored, <code>false</code> otherwise.
     */
    protected boolean shouldIgnoreSlot(Slot slot) {
        return ignoredSlots.contains(slot.n);
    }

    /**
     * Find action for the given slot.
     * @param slot slot to be checked.
     * @return action for the given slot.
     */
    protected abstract MouseAction getMouseActionForSlot(Slot slot);

    /**
     * Inventory slot.
     * @author Marcio Ribeiro (mmr)
     * @created Aug 2, 2008
     */
    class Slot {
        int n;

        int x;

        int y;

        /**
         * Construtor.
         * @param n name.
         * @param j column.
         * @param i row.
         */
        Slot(int n, int j, int i) {
            this.n = n;
            this.x = iniX + INV_X_OFFSET * j;
            this.y = iniY + INV_Y_OFFSET * i;
        }

        /**
         * @return <code>true</code> if slot is in last row, <code>false</code> if not.
         */
        boolean isInLastRow() {
            return y == iniY + INV_Y_OFFSET * (ROWS - 1);
        }

        /**
         * toString.
         * @return slot description.
         */
        @Override
        public String toString() {
            return n + " (" + x + ", " + y + ")";
        }
    }
}
