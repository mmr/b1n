package org.b1n.jirator.domain;

import javax.persistence.Column;
import javax.persistence.Entity;
import javax.persistence.JoinColumn;
import javax.persistence.ManyToOne;

import org.b1n.framework.persistence.SimpleEntity;

/**
 * @author Marcio Ribeiro
 * @date May 3, 2008
 */
@Entity
public class Component extends SimpleEntity {
    @ManyToOne
    @JoinColumn(nullable = false)
    private Project project;

    @Column(nullable = false, unique = true)
    private Long jiraId;

    @Column(nullable = false)
    private String name;

    /**
     * @return the project
     */
    public Project getProject() {
        return project;
    }

    /**
     * @param project the project to set
     */
    public void setProject(final Project project) {
        this.project = project;
    }

    /**
     * @return the jiraId
     */
    public Long getJiraId() {
        return jiraId;
    }

    /**
     * @param jiraId the jiraId to set
     */
    public void setJiraId(final Long jiraId) {
        this.jiraId = jiraId;
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
}
