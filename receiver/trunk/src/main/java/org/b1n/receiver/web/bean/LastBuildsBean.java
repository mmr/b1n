package org.b1n.receiver.web.bean;

import java.util.List;

import org.b1n.framework.persistence.DaoLocator;
import org.b1n.receiver.domain.ProjectBuild;
import org.b1n.receiver.domain.ProjectBuildDao;

/**
 * @author Marcio Ribeiro
 * @date Jan 23, 2008
 */
public class LastBuildsBean {
    private static final int MAX = 20;
    private List<ProjectBuild> builds;

    /**
     * @return lista com ultimos builds.
     */
    public List<ProjectBuild> getBuilds() {
        if (builds == null) {
            ProjectBuildDao buildDao = DaoLocator.getDao(ProjectBuild.class);
            builds = buildDao.findLastBuilds(MAX, 0);
        }
        return builds;
    }
}
