package org.b1n.receiver.web.logic;

import java.text.NumberFormat;
import java.util.ArrayList;
import java.util.Calendar;
import java.util.LinkedHashMap;
import java.util.List;
import java.util.Map;

import org.b1n.framework.persistence.DaoLocator;
import org.b1n.receiver.domain.ProjectBuild;
import org.b1n.receiver.domain.ProjectBuildDao;
import org.vraptor.annotations.Component;
import org.vraptor.annotations.Out;

/**
 * @author Marcio Ribeiro
 * @date Feb 3, 2008
 */
@Component("lastBuilds")
public class LastBuildsLogic {
    private static final int MAX = 100;

    @Out
    private List<Map.Entry<String, List<ProjectBuild>>> buildsByHour;

    /**
     * Carrega lista de builds para ser mostrada.
     */
    public void show() {
        if (buildsByHour == null) {
            organizeBuildsByHour();
        }
    }

    /**
     * Organiza builds por hora.
     */
    private void organizeBuildsByHour() {
        Map<String, List<ProjectBuild>> buildsMap = new LinkedHashMap<String, List<ProjectBuild>>();
        ProjectBuildDao buildDao = DaoLocator.getDao(ProjectBuild.class);
        List<ProjectBuild> bs = buildDao.findLastBuilds(MAX, 0);
        NumberFormat nf = NumberFormat.getInstance();
        nf.setMinimumIntegerDigits(2);
        for (ProjectBuild b : bs) {
            Calendar c = Calendar.getInstance();
            c.setTime(b.getStartTime());
            String hour = nf.format(c.get(Calendar.HOUR_OF_DAY));
            if (!buildsMap.containsKey(hour)) {
                buildsMap.put(hour, new ArrayList<ProjectBuild>());
            }
            buildsMap.get(hour).add(b);
        }
        buildsByHour = new ArrayList<Map.Entry<String, List<ProjectBuild>>>(buildsMap.entrySet());
    }
}
