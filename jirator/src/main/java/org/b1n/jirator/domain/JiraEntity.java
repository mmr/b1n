package org.b1n.jirator.domain;

import javax.persistence.Column;
import javax.persistence.MappedSuperclass;

import org.b1n.framework.persistence.SimpleEntity;

/**
 * Classe base para entidades que possuem uma contra parte no jira.
 * @author Marcio Ribeiro
 * @date May 5, 2008
 */
@MappedSuperclass
public abstract class JiraEntity extends SimpleEntity {
    @Column(nullable = false)
    private Long jiraId;

    /**
     * @return id no jira.
     */
    public Long getJiraId() {
        return jiraId;
    }

    /**
     * Define id no jira.
     * @param jiraId id no jira.
     */
    public void setJiraId(final Long jiraId) {
        this.jiraId = jiraId;
    }
}
