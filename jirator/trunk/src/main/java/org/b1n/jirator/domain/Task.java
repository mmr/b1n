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
public class Task extends SimpleEntity {
    @ManyToOne
    @Column(nullable = false)
    private Round round;

    @ManyToOne
    @Column(nullable = false)
    private Participant participant;

    @Column(nullable = false)
    private Long jiraId;

    @ManyToOne
    @JoinColumn(nullable = false)
    private Priority priority;

    @ManyToOne
    @JoinColumn(nullable = false)
    private Severity severity;

    /**
     * @return the round
     */
    public Round getRound() {
        return round;
    }

    /**
     * @param round the round to set
     */
    public void setRound(final Round round) {
        this.round = round;
    }

    /**
     * @return the participant
     */
    public Participant getParticipant() {
        return participant;
    }

    /**
     * @param participant the participant to set
     */
    public void setParticipant(final Participant participant) {
        this.participant = participant;
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
     * @return the priority
     */
    public Priority getPriority() {
        return priority;
    }

    /**
     * @param priority the priority to set
     */
    public void setPriority(final Priority priority) {
        this.priority = priority;
    }

    /**
     * @return the severity
     */
    public Severity getSeverity() {
        return severity;
    }

    /**
     * @param severity the severity to set
     */
    public void setSeverity(final Severity severity) {
        this.severity = severity;
    }

    /**
     * Calcula e devolve quantidade de pontos que essa tarefa vale.
     * @return total de pontos que essa tarefa vale.
     */
    public double getPointsWorth() {
        return 0d;
    }
}
