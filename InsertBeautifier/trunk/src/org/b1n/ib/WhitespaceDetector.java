package org.b1n.ib;

import org.eclipse.jface.text.rules.IWhitespaceDetector;

/**
 * @author Marcio Ribeiro
 * @date 07/10/2007
 */
public class WhitespaceDetector implements IWhitespaceDetector {

	public boolean isWhitespace(char c) {
		return (c == ' ' || c == '\t' || c == '\n' || c == '\r');
	}
}
