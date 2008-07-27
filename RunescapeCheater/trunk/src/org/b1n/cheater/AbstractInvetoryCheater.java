package org.b1n.cheater;

import java.util.ArrayList;
import java.util.HashSet;
import java.util.List;
import java.util.Set;

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

    private void configureIgnoredSlots(int... slotsToIgnore) {
        this.ignoredSlots = new HashSet<Integer>();
        for (int i : slotsToIgnore) {
            ignoredSlots.add(i);
        }
    }

    private void configureSlots() {
        this.slots = new ArrayList<Slot>();
        int cx = 0;
        for (int i = 0; i < ROWS; i++) {
            for (int j = 0; j < COLS; j++) {
                slots.add(new Slot(cx++, j, i));
            }
        }
    }

    @Override
    protected final MouseAction getMouseAction() {
        MouseActionGroup mainGroup = new MouseActionGroup(mainGroupName);
        for (Slot slot : slots) {
            if (!ignoreSlot(slot)) {
                mainGroup.add(getMouseActionForSlot(slot));
            }
        }
        return mainGroup;
    }

    protected final Slot findSlotByName(int n) {
        for (Slot slot : slots) {
            if (slot.n == n) {
                return slot;
            }
        }
        throw new IllegalStateException("Slot not found : " + n);
    }

    protected final List<Slot> getSlots() {
        return slots;
    }

    protected boolean ignoreSlot(Slot slot) {
        return ignoredSlots.contains(slot.n);
    }

    protected abstract MouseAction getMouseActionForSlot(Slot slot);

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
