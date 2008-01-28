package org.b1n.receiver.web.bean;

import java.text.NumberFormat;
import java.util.ArrayList;
import java.util.Calendar;
import java.util.HashMap;
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
    private Map<String, List<ProjectBuild>> buildsByHour;
    private List<Map.Entry<String, List<ProjectBuild>>> entries;

    /**
     * Construtor.
     */
    public LastBuildsBean() {
        organizeBuildsByHour();
    }

    /**
     * @return mapa com builds indexados por hora.
     */
    public List<Map.Entry<String, List<ProjectBuild>>> getEntries() {
        if (entries == null) {
            entries = new ArrayList<Map.Entry<String, List<ProjectBuild>>>(buildsByHour.entrySet());
        }
        return entries;
    }

    /**
     * Organiza builds por hora.
     */
    private void organizeBuildsByHour() {
        buildsByHour = new HashMap<String, List<ProjectBuild>>();
        ProjectBuildDao buildDao = DaoLocator.getDao(ProjectBuild.class);
        List<ProjectBuild> bs = buildDao.findLastBuilds(MAX, 0);
        NumberFormat nf = NumberFormat.getInstance();
        nf.setMinimumIntegerDigits(2);
        for (ProjectBuild b : bs) {
            Calendar c = Calendar.getInstance();
            c.setTime(b.getStartTime());
            String hour = nf.format(c.get(Calendar.HOUR_OF_DAY));
            if (!buildsByHour.containsKey(hour)) {
                buildsByHour.put(hour, new ArrayList<ProjectBuild>());
            }
            buildsByHour.get(hour).add(b);
        }
    }
}
