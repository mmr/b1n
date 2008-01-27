package org.b1n.receiver.web.bean;

import java.util.ArrayList;
import java.util.Calendar;
import java.util.LinkedHashMap;
import java.util.List;
import java.util.Map;

import org.b1n.framework.persistence.DaoLocator;
import org.b1n.receiver.domain.ProjectBuild;
import org.b1n.receiver.domain.ProjectBuildDao;

/**
 * @author Marcio Ribeiro
 * @date Jan 23, 2008
 */
public class LastBuildsBean {
    private static final int MAX = 20;
    private Map<Integer, List<ProjectBuild>> builds;

    /**
     * @return lista com ultimos builds.
     */
    public Map<Integer, List<ProjectBuild>> getBuilds() {
        if (builds == null) {
            builds = new LinkedHashMap<Integer, List<ProjectBuild>>();
            organizeBuildsByHour();
        }
        return builds;
    }

    private void organizeBuildsByHour() {
        ProjectBuildDao buildDao = DaoLocator.getDao(ProjectBuild.class);
        List<ProjectBuild> bs = buildDao.findLastBuilds(MAX, 0);
        for (ProjectBuild b : bs) {
            Calendar c = Calendar.getInstance();
            c.setTime(b.getStartTime());
            int hour = c.get(Calendar.HOUR_OF_DAY);
            if (!builds.containsKey(hour)) {
                builds.put(hour, new ArrayList<ProjectBuild>());
            }
            builds.get(hour).add(b);
        }
    }
}
