package org.b1n.ib;

import org.eclipse.jface.text.formatter.IFormattingStrategy;

/**
 * @author Marcio Ribeiro
 * @date 07/10/2007
 */
public class DefaultFormattingStrategy implements IFormattingStrategy {

    @Override
    public String format(String content, boolean isLineStart, String indentation, int[] positions) {
        return null;
    }

    @Override
    public void formatterStarts(String initialIndentation) {
    }

    @Override
    public void formatterStops() {
    }
}
