package org.b1n.Cheater;

import java.awt.AWTException;
import java.awt.Robot;
import java.awt.event.InputEvent;
import java.io.File;
import java.util.Date;

/**
 * Rouba arcas de tesouro no RuneScape.
 * :East Ardougne, casa ao norte da igreja, faça lock picking na porta.
 * :No segundo andar, arca da esquerda. 
 * @version $Id: Cheater.java,v 1.2 2005/07/17 11:44:37 mmr Exp $
 * @created 17/07/2005
 * @author Marcio Ribeiro (mmr)
 */
public class Cheater {
	private static final int[][] P = { { 413, 200 }, { 413, 240 } };

	private static final int DELAY = 500;

	private static final int BIG_DELAY = DELAY * 35;
	
	private static final int INITIAL_DELAY = 2000;

	private static final File CHECK_FILE = new File(File.separator + "c.txt");

	private Robot robot = null;

	public Cheater() {
		setupRobot();
	}

	/**
	 * Configura o Robo.
	 */
	private void setupRobot() {
		try {
			robot = new Robot();
			robot.setAutoDelay(DELAY);
		} catch (AWTException e) {
			// do nothing
		}
	}

	/**
	 * Eventos de mouse para roubar as arcas de tesouro.
	 */
	public void robCasket() {
		int i = 0;
		robot.delay(INITIAL_DELAY);

		while (CHECK_FILE.exists()) {
			System.out.println(i + ": " + new Date());
			robot.mouseMove(P[0][0], P[0][1]);
			robot.mousePress(InputEvent.BUTTON3_MASK);
			robot.mouseMove(P[1][0], P[1][1]);
			robot.mouseRelease(InputEvent.BUTTON3_MASK);
			robot.mousePress(InputEvent.BUTTON1_MASK);
			robot.mouseRelease(InputEvent.BUTTON1_MASK);
			robot.delay(BIG_DELAY);
			i++;
		}
	}

	public static void main(String[] args) {
		Cheater cheater = new Cheater();
		cheater.robCasket();
		System.exit(0);
	}
}