package org.b1n.Cheater;

import java.awt.BorderLayout;
import java.awt.MouseInfo;
import java.awt.Point;
import java.awt.Toolkit;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import java.awt.event.MouseEvent;
import java.awt.event.MouseListener;

import javax.swing.JLabel;
import javax.swing.JWindow;
import javax.swing.Timer;

/**
 * Captura posição do Mouse.
 * @created 16/06/2005
 * @version $Id: Cap.java,v 1.5 2006/01/13 01:37:07 mmr Exp $
 * @author Marcio Ribeiro (mmr)
 */
public class Cap {
	private static final long serialVersionUID = 1L;

	private static final int DELAY = 100;

	public static void main(String[] args) {
		final JWindow window = new JWindow();
		window.setAlwaysOnTop(true);

		MouseListener m = new MouseListener() {
			public void mouseClicked(MouseEvent e) {
				window.setVisible(false);
				window.dispose();
				System.exit(-1);
			}

			public void mousePressed(MouseEvent e) {
			}

			public void mouseReleased(MouseEvent e) {
			}

			public void mouseEntered(MouseEvent e) {
			}

			public void mouseExited(MouseEvent e) {
			}
		};
		window.addMouseListener(m);

		ActionListener a = new ActionListener() {
			public void actionPerformed(ActionEvent e) {
				Point location = MouseInfo.getPointerInfo().getLocation();
				String msg = ((int) location.getX()) + "x" + ((int) location.getY());

				JLabel label = (JLabel) window.getContentPane().getComponent(0);
				label.setText(msg);
				window.repaint();
			}
		};

		int screenWidth = (int) Toolkit.getDefaultToolkit().getScreenSize().getWidth();
		int screenHeight = (int) Toolkit.getDefaultToolkit().getScreenSize().getHeight();

		JLabel label = new JLabel(screenWidth + "x" + screenHeight,	JLabel.CENTER);
		window.getContentPane().add(label, BorderLayout.CENTER);
		window.pack();

		int windowWidth = (int) window.getSize().getWidth();
		// int windowHeight = (int) window.getSize().getHeight();

		// window.setLocation(screenWidth - windowWidth, screenHeight -
		// windowHeight);
		window.setLocation(screenWidth - windowWidth, screenHeight / 2);
		window.setVisible(true);

		Timer timer = new Timer(DELAY, a);
		timer.start();
	}
}