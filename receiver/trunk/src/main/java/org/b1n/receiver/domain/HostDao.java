package org.b1n.receiver.domain;

import org.b1n.framework.persistence.EntityNotFoundException;
import org.b1n.framework.persistence.SimpleEntityDao;
import org.hibernate.Criteria;
import org.hibernate.criterion.Restrictions;

/**
 * DAO de Host.
 * @author Marcio Ribeiro
 * @date Jan 21, 2008
 */
public class HostDao extends SimpleEntityDao<Host> {
    /**
     * Devolve o host com o nome passado.
     * @param hostName o nome do host.
     * @return o host com o nome passado.
     * @throws EntityNotFoundException caso nao encontre.
     */
    public Host findByHostName(String hostName) throws EntityNotFoundException {
        Criteria crit = createCriteria();
        crit.add(Restrictions.eq("hostName", hostName));
        Host host = (Host) crit.uniqueResult();
        if (host == null) {
            throw new EntityNotFoundException(User.class);
        }
        return host;
    }
}
