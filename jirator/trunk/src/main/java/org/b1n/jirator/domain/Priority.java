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
public class Priority extends TrackedEntity {
    @Id
    @GeneratedValue
    private Long id;

    @Column(nullable = false, unique = true)
    private Integer jiraValue;

    @Column(nullable = false)
    private Integer value;

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
