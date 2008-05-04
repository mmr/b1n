package org.b1n.jirator.domain;

import javax.persistence.Column;
import javax.persistence.Entity;

import org.b1n.framework.persistence.SimpleEntity;

/**
 * @author Marcio Ribeiro
 * @date May 3, 2008
 */
@Entity
public class Severity extends SimpleEntity {
    @Column(nullable = false)
    private Integer value;

    @Column(nullable = false, unique = true)
    private String jiraName;

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

    /**
     * @return the jiraName
     */
    public String getJiraName() {
        return jiraName;
    }

    /**
     * @param jiraName the jiraName to set
     */
    public void setJiraName(final String jiraName) {
        this.jiraName = jiraName;
    }

    /**
     * @return to string.
     */
    @Override
    public String toString() {
        return jiraName + " (" + value + ")";
    }
}
