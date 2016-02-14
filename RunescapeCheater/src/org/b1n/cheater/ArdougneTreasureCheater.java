package org.b1n.cheater;

/**
 * Rob treasure caskets in Ardougne.
 * For the cheat to work you should place the player in front of the right casket.
 * @author Marcio Ribeiro (mmr)
 * @created Aug 2, 2008
 */
public class ArdougneTreasureCheater extends AbstractActionListCheater {

    private int leftCasketX;

    private int leftCasketY;

    private int rightCasketX;

    private int rightCasketY;

    private static final int FIND_TRAP_X_OFFSET = 30;

    private static final int FIND_TRAP_Y_OFFSET = 25;

    /**
     * Construtor.
     * @param leftCasketX x coord of the left casket.
     * @param leftCasketY y coord of the left casket.
     * @param rightCasketX x coord of the left casket.
     * @param rightCasketY y coord of the left casket.
     */
    public ArdougneTreasureCheater(int leftCasketX, int leftCasketY, int rightCasketX, int rightCasketY) {
        this.leftCasketX = leftCasketX;
        this.leftCasketX = leftCasketY;
        this.rightCasketX = rightCasketX;
        this.rightCasketX = rightCasketY;
    }

    /**
     * @return mouse action to rob caskets.
     */
    @Override
    protected MouseAction getMouseAction() {
        // Left Casket
        MouseActionGroup leftCasket = new MouseActionGroup("Left Casket");
        leftCasket.add(new MouseClick("1", leftCasketX, leftCasketY, MouseButton.RIGHT, 200));
        leftCasket.add(new MouseClick("2", leftCasketX - FIND_TRAP_X_OFFSET, leftCasketY + FIND_TRAP_Y_OFFSET, MouseButton.LEFT, 5000));

        // Right Casket
        MouseActionGroup rightCasket = new MouseActionGroup("Right Casket");
        leftCasket.add(new MouseClick("1", rightCasketX, rightCasketY, MouseButton.RIGHT, 200));
        leftCasket.add(new MouseClick("2", rightCasketX - FIND_TRAP_X_OFFSET, rightCasketY + FIND_TRAP_Y_OFFSET, MouseButton.LEFT, 5000));

        return new MouseActionGroup("Rob Treasures", leftCasket, rightCasket);
    }

    /**
     * Cheat!
     * @param args args.
     */
    public static void main(String[] args) {
        new ArdougneTreasureCheater(190, 333, 300, 333).cheat();
    }
}
