package org.b1n.jirator.domain;

import java.util.Date;
import java.util.List;

import org.hibernate.Criteria;
import org.hibernate.criterion.Restrictions;

/**
 * DAO de tarefa.
 * @author Marcio Ribeiro
 * @date May 3, 2008
 */
public class TaskDao extends JiraEntityDao<Task> {

    /**
     * Encontra tarefas no intervalo de datas passado.
     * @param startDate data inicio.
     * @param endDate data fim.
     * @return lista de tarefas no intervalo de datas passado.
     */
    public List<Task> findByDateInterval(final Date startDate, final Date endDate) {
        Criteria crit = createCriteria();
        crit.add(Restrictions.ge("taskDate", startDate));
        crit.add(Restrictions.le("taskDate", endDate));
        return findByCriteria(crit);
    }
}
