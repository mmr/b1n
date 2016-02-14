package org.b1n.jirator.domain;

import javax.persistence.Column;
import javax.persistence.Entity;
import javax.persistence.GeneratedValue;
import javax.persistence.Id;

import org.b1n.framework.persistence.SimpleEntity;

/**
 * @author Marcio Ribeiro
 * @date May 3, 2008
 */
@Entity
public class Project extends SimpleEntity {
    @Id
    @GeneratedValue
    private Long id;

    @Column(nullable = false, unique = true)
    private String name;

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
     * @return the name
     */
    public String getName() {
        return name;
    }

    /**
     * @param name the name to set
     */
    public void setName(final String name) {
        this.name = name;
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
}
