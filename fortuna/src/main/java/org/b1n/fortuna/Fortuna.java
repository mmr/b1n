package org.b1n.fortuna;

import java.util.Date;

import org.apache.log4j.Logger;
import org.b1n.fortuna.persistence.Game;

/**
 * @author Marcio Ribeiro (mmr)
 * @created Jul 16, 2006
 * @version $Id: Fortuna.java,v 1.1 2006/07/16 03:29:58 mmr Exp $
 */
public class Fortuna {
    private static Logger logger = Logger.getLogger(Game.class);
    public static void main(String[] args) {
        logger.debug("Criando um Game...");
        Game game = new Game();
        game.setDateCreated(new Date());
        game.setDateLastModified(new Date());
        game.setGameNo((int) (Math.random() * 100));
        game.setPlace("Looonge!");
        game.save();
        logger.debug("Game: " + game.getId());
    }
}
