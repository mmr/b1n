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
public class BuildDao extends HibernateEntityDao<Build> {
    /**
     * Devolve lista dos ultimos usuarios cadastrados.
     * @param maxResults maximo de resultados.
     * @param offset offset (para paginacao).
     * @return lista dos ultimos usuarios cadastrados.
     */
    @SuppressWarnings("unchecked")
    public List<Build> findLastAddedBuilds(int maxResults, int offset) {
        Criteria crit = createCriteria();
        crit.addOrder(Order.desc("startTime"));
        crit.setMaxResults(maxResults);
        crit.setFirstResult(offset);
        crit.add(Restrictions.isNotNull("endTime"));
        return crit.list();
    }
}
