package org.b1n.receiver.domain;

import java.util.List;

import org.b1n.framework.persistence.HibernateEntityDao;
import org.hibernate.Criteria;
import org.hibernate.criterion.Order;
import org.hibernate.criterion.Restrictions;

/**
 * @author Marcio Ribeiro
 * @date Jan 21, 2008
 */
public class ProjectBuildDao extends HibernateEntityDao<ProjectBuild> {
    /**
     * Devolve lista de ultimos builds.
     * @param maxResults maximo de resultados.
     * @param offset offset para paginacao.
     * @return lista dos ultimos builds.
     */
    @SuppressWarnings("unchecked")
    public List<ProjectBuild> findLastBuilds(int maxResults, int offset) {
        Criteria crit = createCriteria();
        crit.addOrder(Order.desc("startTime"));
        crit.setMaxResults(maxResults);
        crit.setFirstResult(offset);
        return crit.list();
    }

    /**
     * Devolve lista dos ultimos builds para o usuario passado.
     * @param userId o id do usuario.
     * @param maxResults maximo de resultados.
     * @param offset offset para paginacao.
     * @return lista de ultimos builds.
     */
    @SuppressWarnings("unchecked")
    public List<ProjectBuild> findLastBuildsByUser(Long userId, int maxResults, int offset) {
        Criteria crit = createCriteria();
        crit.add(Restrictions.eq("user.id", userId));
        crit.addOrder(Order.desc("startTime"));
        crit.setMaxResults(maxResults);
        crit.setFirstResult(offset);
        return crit.list();
    }
}
