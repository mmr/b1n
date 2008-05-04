package org.b1n.jirator.domain;

import java.util.Date;
import java.util.List;

import javax.persistence.Column;
import javax.persistence.Entity;
import javax.persistence.JoinColumn;
import javax.persistence.ManyToOne;
import javax.persistence.OneToMany;

import org.b1n.framework.persistence.SimpleEntity;

/**
 * @author Marcio Ribeiro
 * @date May 3, 2008
 */
@Entity
public class Round extends SimpleEntity {
    @ManyToOne
    @JoinColumn(nullable = false)
    private Project project;

    @OneToMany
    @JoinColumn(nullable = false)
    private List<Task> tasks;

    @Column(nullable = false)
    private Date startDate;

    @Column(nullable = false)
    private Date endDate;

    /**
     * @return the project
     */
    public Project getProject() {
        return project;
    }

    /**
     * @param project the project to set
     */
    public void setProject(final Project project) {
        this.project = project;
    }

    /**
     * @return the tasks
     */
    public List<Task> getTasks() {
        return tasks;
    }

    /**
     * @param tasks the tasks to set
     */
    protected void setTasks(final List<Task> tasks) {
        this.tasks = tasks;
    }

    /**
     * Adiciona uma tarefa.
     * @param task tarefa.
     */
    public void addTask(final Task task) {
        this.tasks.add(task);
    }

    /**
     * Remove uma tarefa.
     * @param task tarefa.
     */
    public void removeTask(final Task task) {
        this.tasks.remove(task);
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
