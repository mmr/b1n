package org.b1n.receiver.web.bean;

import java.util.List;

import org.b1n.framework.persistence.DaoLocator;
import org.b1n.receiver.domain.Build;
import org.b1n.receiver.domain.BuildDao;

/**
 * @author Marcio Ribeiro
 * @date Jan 23, 2008
 */
public class LastBuildsBean {
    private List<Build> builds;

    public List<Build> getBuilds() {
        if (builds == null) {
            BuildDao buildDao = DaoLocator.getDao(Build.class);
            builds = buildDao.findLastAddedBuilds(20, 0);
        }
        return builds;
    }
}
