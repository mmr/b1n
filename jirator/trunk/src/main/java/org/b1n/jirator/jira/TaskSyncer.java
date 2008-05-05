package org.b1n.jirator.jira;

import java.math.BigDecimal;
import java.sql.SQLException;
import java.sql.Timestamp;
import java.util.Date;
import java.util.Iterator;
import java.util.List;

import org.apache.log4j.Logger;
import org.b1n.framework.persistence.DaoLocator;
import org.b1n.framework.persistence.EntityNotFoundException;
import org.b1n.jirator.domain.Participant;
import org.b1n.jirator.domain.ParticipantDao;
import org.b1n.jirator.domain.Priority;
import org.b1n.jirator.domain.Severity;
import org.b1n.jirator.domain.Task;
import org.b1n.jirator.domain.TaskDao;

/**
 * Sincroniza com tarefas do jira.
 * @author Marcio Ribeiro
 * @date May 5, 2008
 */
public class TaskSyncer implements JiraSyncer<Participant> {
    // TODO (mmr) : nao deixar id de projeto BWAM hardcoded
    private static final String PROJECT_ID_BWAM = "10082";

    // TODO (mmr) : nao deixar id de status que importam hardcoded
    private static final String STATUSES_QUE_IMPORTAM = "'10002', '10008'";

    // TODO (mmr) : nao deixar id de custom field severidade hardcoded
    private static final String CUSTOM_FIELD_SEVERIDADE_ID = "10020";

    /** Change Item Column Name Status. */
    private static final String ITEM_COLNAME_STATUS = "status";

    private static final String JIRA_ID_ALIAS = "jiraId";

    private static final String USER_LOGIN_ALIAS = "userLogin";

    private static final String JIRA_TASK_KEY_ALIAS = "taskKey";

    private static final String PRIORITY_ALIAS = "priority";

    private static final String SEVERITY_ALIAS = "severity";

    private static final String TASK_DATE_ALIAS = "taskDate";

    private static final Logger LOG = Logger.getLogger(TaskSyncer.class);

    /**
     * Sincroniza com lista de tarefas.
     * @throws CouldNotSyncDataException caso nao consiga trazer dados do jira.
     */
    public void syncData() throws CouldNotSyncDataException {
        ParticipantDao pDao = DaoLocator.getDao(Participant.class);
        List<Participant> participants = pDao.findAll();

        if (participants == null || participants.isEmpty()) {
            throw new IllegalStateException("Nao ha participantes.");
        }

        TaskDao dao = DaoLocator.getDao(Task.class);
        List<Row> jiraTasks = getJiraTasks(participants);
        for (Row row : jiraTasks) {
            Long jiraId = ((BigDecimal) row.get(JIRA_ID_ALIAS)).longValue();
            String userLogin = (String) row.get(USER_LOGIN_ALIAS);
            String jiraKey = (String) row.get(JIRA_TASK_KEY_ALIAS);
            Integer priority = Integer.valueOf((String) row.get(PRIORITY_ALIAS));
            String severity = (String) row.get(SEVERITY_ALIAS);
            Date taskDate = new Date(((Timestamp) row.get(TASK_DATE_ALIAS)).getTime());

            try {
                dao.findByJiraId(jiraId);
            } catch (EntityNotFoundException e) {
                try {
                    Task t = new Task();
                    t.setParticipant(pDao.findByJiraLogin(userLogin));
                    t.setJiraKey(jiraKey);
                    t.setJiraId(jiraId);
                    t.setPriority(Priority.getEnumJiraValue(priority));
                    t.setSeverity(Severity.getEnumJiraValue(severity));
                    t.setTaskDate(taskDate);
                    t.save();
                } catch (Throwable et) {
                    LOG.error("Ignorando tarefa : " + jiraId, et);
                    continue;
                }
            } catch (Throwable e) {
                LOG.error("Ignorando tarefa : " + jiraId, e);
                continue;
            }
        }
    }

    /**
     * Devolve lista de tarefas.
     * @param participants participantes.
     * @return lista de tarefas.
     * @throws CouldNotSyncDataException caso nao consiga trazer dados.
     */
    private List<Row> getJiraTasks(final List<Participant> participants) throws CouldNotSyncDataException {
        try {
            StringBuilder logins = new StringBuilder();

            Iterator<Participant> it = participants.iterator();
            logins.append("'" + it.next().getJiraLogin() + "'");
            while (it.hasNext()) {
                logins.append(", '" + it.next().getJiraLogin() + "'");
            }

            StringBuilder sb = new StringBuilder();
            sb.append(" SELECT");
            sb.append("     t.id            AS " + JIRA_ID_ALIAS + ",");
            sb.append("     t.pkey          AS " + JIRA_TASK_KEY_ALIAS + ",");
            sb.append("     t.priority      AS " + PRIORITY_ALIAS + ",");
            sb.append("     cv.stringvalue  AS " + SEVERITY_ALIAS + ",");
            sb.append("     c.author        AS " + USER_LOGIN_ALIAS + ",");
            sb.append("     c.created       AS " + TASK_DATE_ALIAS);
            sb.append(" FROM");
            sb.append("     jiraissue   t INNER JOIN");
            sb.append("     changegroup c ON (t.id = c.issueid) INNER JOIN");
            sb.append("     changeitem  i ON (c.id = i.groupid) INNER JOIN");
            sb.append("     customfieldvalue cv ON (t.id = cv.issue)");
            sb.append(" WHERE");
            sb.append("     t.project       = '" + PROJECT_ID_BWAM          + "' AND");
            sb.append("     t.issuestatus  IN (" + STATUSES_QUE_IMPORTAM    + ") AND");
            sb.append("     c.author       IN (" + logins.toString()        + ") AND");
            sb.append("     i.oldvalue     IN ('10004') AND");
            sb.append("     i.newvalue     IN ('10008') AND");
            sb.append("     i.field         = '" + ITEM_COLNAME_STATUS      + "' AND");
            sb.append("     cv.customfield  = '" + CUSTOM_FIELD_SEVERIDADE_ID   + "'");

            return JiraGateway.executeQuery(sb.toString(), JIRA_ID_ALIAS, JIRA_TASK_KEY_ALIAS, PRIORITY_ALIAS, SEVERITY_ALIAS, USER_LOGIN_ALIAS, TASK_DATE_ALIAS);
        } catch (SQLException e) {
            throw new CouldNotSyncDataException(e);
        }
    }
}
