package org.b1n.receiver.domain;

import javax.persistence.Column;
import javax.persistence.Entity;
import javax.persistence.Table;

import org.b1n.framework.persistence.RecordEntity;

/**
 * @author Marcio Ribeiro
 * @date Jan 23, 2008
 */
@Entity
@Table(name = "user_")
public class User extends RecordEntity {
    @Column(nullable = false, unique = true)
    private String userName;

    public User(String userName) {
        this.userName = userName;
    }

    public String getUserName() {
        return userName;
    }

    public void setUserName(String userName) {
        this.userName = userName;
    }

    @Override
    public String toString() {
        return userName;
    }
}
