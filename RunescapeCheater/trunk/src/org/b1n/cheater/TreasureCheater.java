package org.b1n.cheater;

public class TreasureCheater extends AbstractActionListCheater {

    @Override
    protected MouseAction getMouseAction() {
        // Left Casket
        MouseActionGroup leftCasket = new MouseActionGroup("Left Casket");
        leftCasket.add(new MouseClick("1", 190, 333, MouseButton.RIGHT, 200));
        leftCasket.add(new MouseClick("2", 164, 373, MouseButton.LEFT, 5000));

        // Right Casket
        MouseActionGroup rightCasket = new MouseActionGroup("Right Casket");
        rightCasket.add(new MouseClick("1", 300, 333, MouseButton.RIGHT, 200));
        rightCasket.add(new MouseClick("2", 275, 375, MouseButton.LEFT, 7000));

        return new MouseActionGroup("Rob Treasures", leftCasket, rightCasket);
    }

    public static void main(String[] args) {
        new TreasureCheater().cheat();
    }
}
