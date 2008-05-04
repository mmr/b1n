package org.b1n.jirator.domain;

import javax.persistence.Column;
import javax.persistence.Entity;

import org.b1n.framework.persistence.SimpleEntity;

/**
 * @author Marcio Ribeiro
 * @date May 3, 2008
 */
@Entity
public class Priority extends SimpleEntity {
    @Column(nullable = false, unique = true)
    private Integer jiraValue;

    @Column(nullable = false)
    private Integer value;

    /**
     * @return the jiraValue
     */
    public Integer getJiraValue() {
        return jiraValue;
    }

    /**
     * @param jiraValue the jiraValue to set
     */
    public void setJiraValue(final Integer jiraValue) {
        this.jiraValue = jiraValue;
    }

    /**
     * @return the value
     */
    public Integer getValue() {
        return value;
    }

    /**
     * @param value the value to set
     */
    public void setValue(final Integer value) {
        this.value = value;
    }
}
