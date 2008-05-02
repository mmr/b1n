package org.b1n.jirator.domain;

import javax.persistence.Column;
import javax.persistence.Entity;
import javax.persistence.GeneratedValue;
import javax.persistence.Id;

import org.b1n.framework.persistence.TrackedEntity;

/**
 * @author Marcio Ribeiro
 * @date May 2, 2008
 */
@Entity
public class Severity extends TrackedEntity {
    @Id
    @GeneratedValue
    private Long id;

    @Column(nullable = false)
    private Integer value;

    @Column(nullable = false, unique = true)
    private String jiraName;

    /**
     * @return the id
     */
    public Long getId() {
        return id;
    }

    /**
     * @param id the id to set
     */
    public void setId(final Long id) {
        this.id = id;
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
