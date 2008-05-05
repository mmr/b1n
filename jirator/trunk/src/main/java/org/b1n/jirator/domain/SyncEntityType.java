package org.b1n.jirator.domain;

/**
 * Tipo de entidade a ser sincronizada com o jira.
 * @author Marcio Ribeiro
 * @date May 4, 2008
 */
public enum SyncEntityType {
    /** Jira issue. */
    TASK,

    /** Jira project. */
    PROJECT,

    /** Prioridade. */
    PRIORITY,

    /** Severidade (Custom field). */
    SEVERITY,

    /** Jira user. */
    USER;
}
