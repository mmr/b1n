package org.b1n.receiver.domain;

import org.b1n.framework.persistence.EntityNotFoundException;
import org.b1n.framework.persistence.HibernateEntityDao;
import org.hibernate.Criteria;
import org.hibernate.criterion.Restrictions;

/**
 * @author Marcio Ribeiro
 * @date Jan 21, 2008
 */
public class UserDao extends HibernateEntityDao<User> {
    @SuppressWarnings("unchecked")
    public User findByUserName(String userName) throws EntityNotFoundException {
        Criteria crit = createCriteria();
        crit.add(Restrictions.eq("userName", userName));
        User user = (User) crit.uniqueResult();
        if (user == null) {
            throw new EntityNotFoundException(User.class);
        }
        return user;
    }
}
