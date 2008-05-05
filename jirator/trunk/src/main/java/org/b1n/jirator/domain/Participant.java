package org.b1n.jirator.domain;

import javax.persistence.Column;
import javax.persistence.Entity;

/**
 * Participante de competicoes.
 * @author Marcio Ribeiro
 * @date May 3, 2008
 */
@Entity
public class Participant extends JiraEntity {
    @Column(nullable = false, unique = true)
    private String jiraLogin;

    @Column(nullable = false, unique = true)
    private String userName;

    /**
     * @return the jiraLogin
     */
    public String getJiraLogin() {
        return jiraLogin;
    }

    /**
     * @param jiraLogin the jiraLogin to set
     */
    public void setJiraLogin(final String jiraLogin) {
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

    /**
     * @return to string.
     */
    @Override
    public String toString() {
        return userName + " (" + jiraLogin + ")";
    }
}
