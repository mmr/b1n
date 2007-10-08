package org.b1n.ib.editor;

import org.eclipse.jface.text.rules.IWhitespaceDetector;

/**
 * Detector de espacos.
 * @author Marcio Ribeiro
 * @date 07/10/2007
 */
public class WhitespaceDetector implements IWhitespaceDetector {

    /**
     * Devolve true se caracter passado for espaco, false se nao.
     * @param c caracter a ser testado.
     * @return true se for espaco, false se nao.
     */
    public boolean isWhitespace(final char c) {
        return ((c == ' ') || (c == '\t') || (c == '\n') || (c == '\r'));
    }
}
