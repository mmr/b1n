package org.b1n.fortuna.persistence;

import java.util.Date;

import javax.persistence.Entity;
import javax.persistence.GeneratedValue;
import javax.persistence.Id;

/**
 * @author Marcio Ribeiro (mmr)
 * @created Jul 15, 2006
 * @version $Id: PersistableObject.java,v 1.1 2006/07/16 03:29:58 mmr Exp $
 */
@Entity
public abstract class PersistableObject {

    private Long id;

    private Date dateCreated;

    private Date dateLastModified;

    @Id
    @GeneratedValue
    public Long getId() {
        return id;
    }

    public void setId(Long id) {
        this.id = id;
    }

    public void save() {
        HibernateUtil.getSession().save(this);
    }

    public void remove() {
        HibernateUtil.getSession().delete(this);
    }
    
    /**
     * @return the dateCreated
     */
    public Date getDateCreated() {
        return dateCreated;
    }

    /**
     * @param dateCreated the dateCreated to set
     */
    public void setDateCreated(Date dateCreated) {
        this.dateCreated = dateCreated;
    }

    /**
     * @return the dateLastModified
     */
    public Date getDateLastModified() {
        return dateLastModified;
    }

    /**
     * @param dateLastModified the dateLastModified to set
     */
    public void setDateLastModified(Date dateLastModified) {
        this.dateLastModified = dateLastModified;
    }
}