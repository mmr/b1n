package org.b1n.receiver.domain;

import javax.persistence.Column;
import javax.persistence.Entity;
import javax.persistence.Table;

import org.b1n.framework.persistence.RecordEntity;

/**
 * Usuario.
 * @author Marcio Ribeiro
 * @date Jan 23, 2008
 */
@Entity
@Table(name = "user_")
public class User extends RecordEntity {
    @Column(nullable = false, unique = true)
    private String userName;

    /**
     * Construtor default para o Hibernate.
     */
    public User() {
        // nothing
    }
    
    /**
     * Construtor.
     * @param userName nome do usuario.
     */
    public User(String userName) {
        this.userName = userName;
    }

    /**
     * @return nome do usuario.
     */
    public String getUserName() {
        return userName;
    }

    /**
     * Define nome do usuario.
     * @param userName o nome do usuario que esta fazendo o build.
     */
    public void setUserName(String userName) {
        this.userName = userName;
    }

    /**
     * @return toString.
     */
    @Override
    public String toString() {
        return userName;
    }
}
