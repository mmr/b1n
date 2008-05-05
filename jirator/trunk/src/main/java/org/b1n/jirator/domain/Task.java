package org.b1n.jirator.domain;

import java.util.Date;

import javax.persistence.Column;
import javax.persistence.Entity;
import javax.persistence.JoinColumn;
import javax.persistence.ManyToOne;

/**
 * Tarefa.
 * @author Marcio Ribeiro
 * @date May 3, 2008
 */
@Entity
public class Task extends JiraEntity {
    @ManyToOne
    @JoinColumn(nullable = false)
    private Participant participant;

    @Column(nullable = false)
    private String jiraKey;

    @Column(nullable = false)
    private Priority priority;

    @Column(nullable = false)
    private Severity severity;

    @Column(nullable = false)
    private Date taskDate;

    /**
     * @return the jiraKey
     */
    public String getJiraKey() {
        return jiraKey;
    }

    /**
     * @param jiraKey the jiraKey to set
     */
    public void setJiraKey(final String jiraKey) {
        this.jiraKey = jiraKey;
    }

    /**
     * @return the taskDate
     */
    public Date getTaskDate() {
        return taskDate;
    }

    /**
     * @param taskDate the taskDate to set
     */
    public void setTaskDate(final Date taskDate) {
        this.taskDate = taskDate;
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
        return severity.getValue() + (priority.getValue() * 2);
    }

    /**
     * @return to string.
     */
    @Override
    public String toString() {
        return participant + ": " + jiraKey + ", " + priority + ", " + severity;
    }
}
