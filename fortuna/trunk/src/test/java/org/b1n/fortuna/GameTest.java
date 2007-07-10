package org.b1n.fortuna;

import java.util.Date;

import junit.framework.TestCase;

import org.apache.log4j.Logger;
import org.b1n.fortuna.persistence.Game;

/**
 * @author Marcio Ribeiro (mmr)
 * @created Jul 16, 2006
 * @version $Id: GameTest.java,v 1.1 2006/07/16 03:29:57 mmr Exp $
 */
public class GameTest extends TestCase {
    private static Logger logger = Logger.getLogger(Game.class);

    public void testGame() {
        logger.debug("Criando Game...");
        Game game = new Game();
        game.setDateCreated(new Date());
        game.setDateLastModified(new Date());
        game.setGameNo((int) (Math.random() * 100));
        game.setPlace("Looonge!");
        game.save();
        logger.debug("Game: " + game.getId());
    }
}
