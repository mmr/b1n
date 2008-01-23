package org.b1n.receiver.domain;

import org.b1n.framework.persistence.EntityNotFoundException;
import org.b1n.framework.persistence.SimpleEntityDao;
import org.hibernate.Criteria;
import org.hibernate.criterion.Restrictions;

/**
 * @author Marcio Ribeiro
 * @date Jan 21, 2008
 */
public class HostDao extends SimpleEntityDao<Host> {
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
