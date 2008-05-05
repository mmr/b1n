package org.b1n.jirator.domain;

import org.b1n.framework.persistence.EntityNotFoundException;
import org.hibernate.Criteria;
import org.hibernate.criterion.Restrictions;

/**
 * DAO de participante de competicoes.
 * @author Marcio Ribeiro
 * @date May 3, 2008
 */
public class ParticipantDao extends JiraEntityDao<Participant> {
    /**
     * Devolve usuario com login do jira igual ao passado.
     * @param jiraLogin login do jira.
     * @return usuario com login do jira igual ao passado.
     * @throws EntityNotFoundException caso nao encontre usuario com o login passado.
     */
    public Participant findByJiraLogin(final String jiraLogin) throws EntityNotFoundException {
        Criteria crit = createCriteria();
        crit.add(Restrictions.eq("jiraLogin", jiraLogin));
        return findByCriteriaSingle(crit);
    }
}
