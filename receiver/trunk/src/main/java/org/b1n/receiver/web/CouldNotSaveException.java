package org.b1n.receiver.web;

/**
 * Caso nao consiga salvar dados.
 * @author Marcio Ribeiro
 * @date Jan 21, 2008
 */
public class CouldNotSaveException extends Exception {
    /**
     * Construtor.
     * @param e causa.
     */
    public CouldNotSaveException(Throwable e) {
        super(e);
    }
}
