package org.b1n.jirator.domain;

import javax.persistence.Entity;

import org.b1n.framework.persistence.SimpleEntity;

/**
 * Historico de sincronizacoes com jira.
 * @author Marcio Ribeiro
 * @date May 4, 2008
 */
@Entity
public class Sync extends SimpleEntity {
    private SyncEntityType type;

    /**
     * @return tipo de entidade de sincronia.
     */
    public SyncEntityType getType() {
        return type;
    }

    /**
     * Define tipo de entidade.
     * @param type tipo de entidade.
     */
    public void setType(final SyncEntityType type) {
        this.type = type;
    }
}
