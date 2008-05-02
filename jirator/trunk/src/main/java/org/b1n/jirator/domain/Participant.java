package org.b1n.jirator.domain;

import javax.persistence.Column;
import javax.persistence.Entity;
import javax.persistence.GeneratedValue;
import javax.persistence.Id;

import org.b1n.framework.persistence.RecordEntity;

/**
 * @author Marcio Ribeiro
 * @date May 2, 2008
 */
@Entity
public class Participant extends RecordEntity {
    @Id
    @GeneratedValue
    private Long id;

    @Column(nullable = false)
    private Long jiraId;

    private Long jiraLogin;

    private String userName;

    /**
     * @return o id.
     */
    public Long getId() {
        return this.id;
    }

    /**
     * Define o id.
     * @param id o id.
     */
    public void setId(final Long id) {
        this.id = id;
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
     * @return the jiraLogin
     */
    public Long getJiraLogin() {
        return jiraLogin;
    }

    /**
     * @param jiraLogin the jiraLogin to set
     */
    public void setJiraLogin(final Long jiraLogin) {
        this.jiraLogin = jiraLogin;
    }

    /**
     * @return the userName
     */
    public String getUserName() {
        return userName;
    }

    /**
     * @param userName the userName to set
     */
    public void setUserName(final String userName) {
        this.userName = userName;
    }
}
