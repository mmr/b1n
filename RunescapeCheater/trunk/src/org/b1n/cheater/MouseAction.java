package org.b1n.cheater;

/**
 * Mouse action interface.
 * @author Marcio Ribeiro (mmr)
 * @created Aug 2, 2008
 */
public interface MouseAction {
    /**
     * Run action.
     */
    void run();

    /**
     * @return name of the action.
     */
    String getName();
}
