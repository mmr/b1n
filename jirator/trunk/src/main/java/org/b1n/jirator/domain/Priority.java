package org.b1n.jirator.domain;

/**
 * Prioridade.
 * @author Marcio Ribeiro
 * @date May 3, 2008
 */
public enum Priority {
    /** Nao definida. */
    UNDEFINED("Não definida", null, 0),

    /** Muito baixa. */
    MUITO_BAIXA("Muito Baixa", 5, 1),

    /** Baixa. */
    BAIXA("Baixa", 4, 2),

    /** Média. */
    MEDIA("Média", 3, 3),

    /** Alta. */
    ALTA("Alta", 2, 4),

    /** Crítica. */
    CRITICA("Crítica", 1, 5);

    private String name;

    private Integer value;

    private Object jiraValue;

    /**
     * Construtor.
     * @param name nome.
     * @param jiraValue valor no jira.
     * @param value valor.
     */
    Priority(final String name, final Object jiraValue, final Integer value) {
        this.name = name;
        this.value = value;
        this.jiraValue = jiraValue;
    }

    /**
     * @return the name
     */
    public String getName() {
        return name;
    }

    /**
     * @return the value
     */
    public Integer getValue() {
        return value;
    }

    /**
     * @return the jiraValue
     */
    public Object getJiraValue() {
        return jiraValue;
    }

    /**
     * @return to string.
     */
    @Override
    public String toString() {
        return name + " (" + value + ")";
    }
}
