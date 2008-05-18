package org.b1n.jirator.domain;

/**
 * Complexidade.
 * @author Marcio Ribeiro
 * @date May 3, 2008
 */
public enum Complexity {
    /** Nao definida. */
    UNDEFINED("Não definida", null, 0),

    /** Muito baixa. */
    MUITO_BAIXA("Muito Baixa", "Muito baixa", 1),

    /** Baixa. */
    BAIXA("Baixa", "Baixa", 2),

    /** Média. */
    MEDIA("Média", "Média", 3),

    /** Alta. */
    ALTA("Alta", "Alta", 4),

    /** Crítica. */
    CRITICA("Muito Alta", "Muito alta", 5);

    private String name;

    private Integer value;

    private Object jiraValue;

    /**
     * Construtor.
     * @param name nome.
     * @param jiraValue valor no jira.
     * @param value valor.
     */
    Complexity(final String name, final Object jiraValue, final Integer value) {
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

    /**
     * Encontra enum para severidade do jira.
     * @param jiraValue valor do jira.
     * @return enum.
     */
    public static Complexity getEnumJiraValue(final Object jiraValue) {
        for (Complexity o : Complexity.values()) {
            if ((jiraValue == null && o.getJiraValue() == null) || o.getJiraValue().equals(jiraValue)) {
                return o;
            }
        }
        throw new IllegalStateException("Complexidade nao encontrada para valor '" + jiraValue + "'");
    }
}
