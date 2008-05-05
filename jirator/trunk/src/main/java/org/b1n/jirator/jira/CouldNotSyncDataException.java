package org.b1n.jirator.jira;

/**
 * Caso nao consiga importar dados.
 * @author Marcio Ribeiro
 * @date May 4, 2008
 */
public class CouldNotSyncDataException extends Exception {

    /**
     * Construtor.
     * @param message mensagem.
     * @param cause causa.
     */
    public CouldNotSyncDataException(final String message, final Throwable cause) {
        super(message, cause);
    }

    /**
     * Construtor.
     * @param message mensagem.
     */
    public CouldNotSyncDataException(final String message) {
        super(message);
    }

    /**
     * Construtor.
     * @param cause causa.
     */
    public CouldNotSyncDataException(final Throwable cause) {
        super(cause);
    }
}
