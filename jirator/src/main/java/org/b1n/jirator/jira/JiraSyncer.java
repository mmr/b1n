package org.b1n.jirator.jira;

import org.b1n.framework.persistence.Entity;

/**
 * Interface base para trazedores de dados do jira.
 * @author Marcio Ribeiro
 * @date May 5, 2008
 * @param <E> tipo de entidade.
 */
public interface JiraSyncer<E extends Entity> {
    /**
     * @throws CouldNotSyncDataException caso nao consiga trazer dados do jira.
     */
    void syncData() throws CouldNotSyncDataException;
}
