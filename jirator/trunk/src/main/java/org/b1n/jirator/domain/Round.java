package org.b1n.jirator.domain;

import java.util.Date;
import java.util.List;

import javax.persistence.Column;
import javax.persistence.Entity;
import javax.persistence.GeneratedValue;
import javax.persistence.Id;
import javax.persistence.JoinColumn;
import javax.persistence.OneToMany;

import org.b1n.framework.persistence.TrackedEntity;

/**
 * @author Marcio Ribeiro
 * @date May 2, 2008
 */
@Entity
public class Round extends TrackedEntity {
    @Id
    @GeneratedValue
    private Long id;

    @OneToMany
    @JoinColumn(nullable = false)
    private List<Participant> participants;

    @Column(nullable = false, unique = true)
    private Date startDate;

    @Column(nullable = false, unique = true)
    private Date endDate;

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
     * @return the participants
     */
    public List<Participant> getParticipants() {
        return participants;
    }

    /**
     * @param participants the participants to set
     */
    public void setParticipants(final List<Participant> participants) {
        this.participants = participants;
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
