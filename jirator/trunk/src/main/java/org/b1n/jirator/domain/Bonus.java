package org.b1n.jirator.domain;

import java.util.Date;

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
public class Bonus extends SimpleEntity {
    @Id
    @GeneratedValue
    private Long id;

    @Column(nullable = false)
    private BonusType type;

    @Column(nullable = false)
    private Double value;

    @Column(nullable = false)
    private Date startDate;

    private Date endDate;

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
     * @return the type
     */
    public BonusType getType() {
        return type;
    }

    /**
     * @param type the type to set
     */
    public void setType(final BonusType type) {
        this.type = type;
    }

    /**
     * @return the value
     */
    public Double getValue() {
        return value;
    }

    /**
     * @param value the value to set
     */
    public void setValue(final Double value) {
        this.value = value;
    }

    /**
     * @return the startDate
     */
    public Date getStartDate() {
        return startDate;
    }

    /**
     * @param startDate the startDate to set
     */
    public void setStartDate(final Date startDate) {
        this.startDate = startDate;
    }

    /**
     * @return the endDate
     */
    public Date getEndDate() {
        return endDate;
    }

    /**
     * @param endDate the endDate to set
     */
    public void setEndDate(final Date endDate) {
        this.endDate = endDate;
    }
}
