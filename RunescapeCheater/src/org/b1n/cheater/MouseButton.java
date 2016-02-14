package org.b1n.cheater;

import java.awt.event.InputEvent;

/**
 * Mouse button.
 * @author Marcio Ribeiro (mmr)
 * @created Aug 2, 2008
 */
enum MouseButton {
    /** Right mouse button. */
    RIGHT(InputEvent.BUTTON3_MASK),

    /** Left mouse button. */
    LEFT(InputEvent.BUTTON1_MASK);

    private int mask;

    /**
     * Construtor.
     * @param mask mask.
     */
    MouseButton(int mask) {
        this.mask = mask;
    }

    /**
     * @return mask.
     */
    int getMask() {
        return mask;
    }

    /**
     * Find mouse button for given mask.
     * @param mask mask.
     * @return mouse button for awt input event mask.
     */
    public static MouseButton getByMask(int mask) {
        for (MouseButton b : MouseButton.values()) {
            if (b.getMask() == mask) {
                return b;
            }
        }
        throw new IllegalStateException("Invalid mask : " + mask);
    }
}
