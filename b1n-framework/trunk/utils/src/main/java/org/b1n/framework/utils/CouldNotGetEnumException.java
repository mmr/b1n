package org.b1n.framework.utils;

/**
 * Lancada caso nao consiga encontrar enum para valor.
 * @author Marcio Ribeiro
 * @date May 18, 2008
 */
public class CouldNotGetEnumException extends RuntimeException {
    /**
     * @param e causa.
     */
    public CouldNotGetEnumException(final Throwable e) {
        super(e);
    }

    /**
     * @param msg mensagem.
     */
    public CouldNotGetEnumException(final String msg) {
        super(msg);
    }

    /**
     * @param msg mensagem.
     * @param e causa.
     */
    public CouldNotGetEnumException(final String msg, final Throwable e) {
        super(msg, e);
    }
}
