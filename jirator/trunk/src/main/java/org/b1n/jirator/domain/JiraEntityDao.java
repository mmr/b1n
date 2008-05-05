package org.b1n.jirator.domain;

import org.b1n.framework.persistence.EntityNotFoundException;
import org.b1n.framework.persistence.SimpleEntityDao;
import org.hibernate.Criteria;
import org.hibernate.criterion.Restrictions;

/**
 * @author Marcio Ribeiro
 * @date May 5, 2008
 * @param <E> tipo de entidade.
 */
public abstract class JiraEntityDao<E extends JiraEntity> extends SimpleEntityDao<E> {
    /**
     * Encontra usuario a partir de id do jira.
     * @param jiraId id do jira.
     * @return usuario com o id passado.
     * @throws EntityNotFoundException caso nao encontre usuario com o id de jira passado.
     */
    public E findByJiraId(final Long jiraId) throws EntityNotFoundException {
        Criteria crit = createCriteria();
        crit.add(Restrictions.eq("jiraId", jiraId));
        return findByCriteriaSingle(crit);
    }
}
