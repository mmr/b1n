package org.b1n.receiver.domain;

import org.b1n.framework.persistence.EntityNotFoundException;
import org.b1n.framework.persistence.RecordEntityDao;
import org.hibernate.Criteria;
import org.hibernate.criterion.Restrictions;

/**
 * @author Marcio Ribeiro
 * @date Jan 21, 2008
 */
public class ProjectDao extends RecordEntityDao<Project> {

    public Project findByKey(String groupId, String artifactId, String version) throws EntityNotFoundException {
        Criteria crit = createCriteria();
        crit.add(Restrictions.eq("groupId", groupId));
        crit.add(Restrictions.eq("artifactId", artifactId));
        crit.add(Restrictions.eq("version", version));

        Project project = (Project) crit.uniqueResult();
        if (project == null) {
            throw new EntityNotFoundException(Project.class);
        }
        return project;
    }
}
