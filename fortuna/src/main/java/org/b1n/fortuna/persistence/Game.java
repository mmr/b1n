package org.b1n.fortuna.persistence;

import javax.persistence.Entity;

/**
 * @author Marcio Ribeiro (mmr)
 * @created Jul 15, 2006
 * @version $Id: Game.java,v 1.1 2006/07/16 03:29:58 mmr Exp $
 */
@Entity
public class Game extends PersistableObject {
    private int gameNo;

    private String place;

    /**
     * @return the gameNo
     */
    public int getGameNo() {
        return gameNo;
    }

    /**
     * @param gameNo the gameNo to set
     */
    public void setGameNo(int gameNo) {
        this.gameNo = gameNo;
    }

    /**
     * @return the place
     */
    public String getPlace() {
        return place;
    }

    /**
     * @param place the place to set
     */
    public void setPlace(String place) {
        this.place = place;
    }
    
}