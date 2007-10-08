package org.b1n.ib.editor;

import org.eclipse.jface.text.formatter.IFormattingStrategy;

/**
 * Formatador dummy.
 * @author Marcio Ribeiro
 * @date 07/10/2007
 */
public class DefaultFormattingStrategy implements IFormattingStrategy {

    /**
     * Devolve conteudo formatado.
     * @param content conteudo a ser formatado.
     * @param isLineStart true se for comeco de linha, false se nao.
     * @param indentation endentacao.
     * @param positions posicoes.
     * @return conteudo formatado.
     */
    public String format(final String content, final boolean isLineStart, final String indentation, final int[] positions) {
        return null;
    }

    /**
     * Chamado quando a formatacao iniciar.
     * @param initialIndentation endentacao inicial.
     */
    public void formatterStarts(final String initialIndentation) {
        // do nothing
    }

    /**
     * Chamado quando a formatacao terminar.
     */
    public void formatterStops() {
        // do nothing
    }
}