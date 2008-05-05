package org.b1n.jirator.web.logic;

import java.util.Comparator;
import java.util.Set;
import java.util.TreeSet;

import org.b1n.jirator.domain.Participant;
import org.b1n.jirator.domain.Task;

/**
 * Classe utilitaria que representa as listas de tarefas completas por um participante.
 * @author Marcio Ribeiro
 * @date May 5, 2008
 */
public class TaskList {
    private Participant participant;

    private Set<Task> tasks;

    private Double points;

    /**
     * Construtor.
     * @param participant participante.
     * @param task tarefa.
     */
    TaskList(final Participant participant, final Task task) {
        this.participant = participant;

        // Mantem tarefas ordenadas por pontuacao
        this.tasks = new TreeSet<Task>(new Comparator<Task>() {
            public int compare(final Task o1, final Task o2) {
                if (o1.getPointsWorth() == o2.getPointsWorth()) {
                    return 0;
                } else if (o1.getPointsWorth() > o2.getPointsWorth()) {
                    return -1;
                } else {
                    return 1;
                }
            }
        });

        // Adiciona tarefa
        this.addTask(task);
    }

    /**
     * Adiciona tarefa a lista de tarefas.
     * @param task tarefa.
     */
    void addTask(final Task task) {
        this.tasks.add(task);
    }

    /**
     * @return o participante.
     */
    public Participant getParticipant() {
        return participant;
    }

    /**
     * @return as tarefas.
     */
    public Set<Task> getTasks() {
        return tasks;
    }

    /**
     * @return somatoria de pontos de tarefas de usuario.
     */
    public Double getTotalPoints() {
        // TODO (mmr) : aplicar bonus global!
        if (points == null) {
            points = 0d;
            for (Task task : tasks) {
                points += task.getPointsWorth();
            }
        }
        return points;
    }

    /**
     * @return quantidade de tarefas.
     */
    public int getTasksAmount() {
        return tasks.size();
    }

    /**
     * @return to string.
     */
    @Override
    public String toString() {
        return participant + ": " + tasks;
    }
}
