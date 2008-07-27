package org.b1n.cheater;

import java.awt.event.InputEvent;

enum MouseButton {
    RIGHT(InputEvent.BUTTON3_MASK), LEFT(InputEvent.BUTTON1_MASK);

    private int mask;

    MouseButton(int mask) {
        this.mask = mask;
    }

    int getMask() {
        return mask;
    }

    public static MouseButton getByMask(int mask) {
        for (MouseButton b : MouseButton.values()) {
            if (b.getMask() == mask) {
                return b;
            }
        }
        throw new IllegalStateException("Invalid mask : " + mask);
    }
}
