package org.b1n.jirator.jira;

import java.lang.reflect.InvocationTargetException;
import java.lang.reflect.Method;
import java.math.BigDecimal;
import java.sql.SQLException;
import java.sql.Timestamp;
import java.util.Date;
import java.util.HashMap;
import java.util.Iterator;
import java.util.List;
import java.util.Map;

import org.apache.log4j.Logger;
import org.b1n.framework.persistence.DaoLocator;
import org.b1n.framework.persistence.EntityNotFoundException;
import org.b1n.jirator.domain.Complexity;
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

    /** Tarefas com esse status devem ser importadas: '10002': Encerrada, 10008: Aguardando Liberacao. */
    private static final String STATUSES_QUE_IMPORTAM = "'10002', '10008'";

    /** Custom Field: Severidade. */
    private static final String CF_SEVERIDADE = "10020";

    /** Custom Field: Complexidade. */
    private static final String CF_COMPLEXIDADE = "10080";

    /** Status: 'Em Desenvolvimento'. */
    private static final String STATUS_EM_DESENV = "10004";

    /** Status: 'Aguardando Liberacao'. */
    private static final String STATUS_AG_LIBER = "10008";

    /** Change Item Column Name Status. */
    private static final String ITEM_COLNAME_STATUS = "status";

    private static final String JIRA_ID_ALIAS = "jiraId";

    private static final String USER_LOGIN_ALIAS = "userLogin";

    private static final String JIRA_TASK_KEY_ALIAS = "taskKey";

    private static final String PRIORITY_ALIAS = "priority";

    private static final String SEVERITY_ALIAS = "severity";

    private static final String COMPLEXITY_ALIAS = "complexity";

    private static final String TASK_DATE_ALIAS = "taskDate";

    private static final Logger LOG = Logger.getLogger(TaskSyncer.class);

    private final Map<String, Participant> participantsCache = new HashMap<String, Participant>();

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

        for (Participant participant : participants) {
            participantsCache.put(participant.getJiraLogin(), participant);
        }

        TaskDao dao = DaoLocator.getDao(Task.class);
        List<Row> jiraTasks = getJiraTasks();
        for (Row row : jiraTasks) {
            Long jiraId = ((BigDecimal) row.get(JIRA_ID_ALIAS)).longValue();
            try {
                dao.findByJiraId(jiraId);
            } catch (EntityNotFoundException e) {
                createNewTask(row, jiraId);
            }
        }
    }

    /**
     * Cria nova tarefa para a linha passada.
     * @param row linha.
     * @param jiraId id do jira.
     * @throws EntityNotFoundException
     */
    private void createNewTask(final Row row, final Long jiraId) {
        try {
            String userLogin = (String) row.get(USER_LOGIN_ALIAS);
            String jiraKey = (String) row.get(JIRA_TASK_KEY_ALIAS);
            Date taskDate = new Date(((Timestamp) row.get(TASK_DATE_ALIAS)).getTime());
            Integer priority = Integer.valueOf((String) row.get(PRIORITY_ALIAS));
            String severity = (String) row.get(SEVERITY_ALIAS);
            String complexity = (String) row.get(COMPLEXITY_ALIAS);

            Task t = new Task();
            t.setParticipant(participantsCache.get(userLogin));
            t.setJiraKey(jiraKey);
            t.setJiraId(jiraId);
            t.setPriority(getEnumByValue(Priority.class, priority, "getJiraValue"));
            t.setSeverity(getEnumByValue(Severity.class, severity, "getJiraValue"));
            t.setComplexity(getEnumByValue(Complexity.class, complexity, "getJiraValue"));
            t.setTaskDate(taskDate);

            t.save();
        } catch (Throwable e) {
            LOG.error("Ignorando tarefa : " + jiraId, e);
        }
    }

    /**
     * Pega enum por valor.
     * @param <E> tipo de enum.
     * @param e classe de enum.
     * @param value valor.
     * @param valueMethod nome de metodo de valor do enum.
     * @return enum.
     * @throws NoSuchMethodException
     * @throws InvocationTargetException
     * @throws NoSuchMethodException no such method.
     * @throws InvocationTargetException invocation target.
     * @throws IllegalAccessException illegal access.
     */
    @SuppressWarnings("unchecked")
    private <E extends Enum<E>> E getEnumByValue(final Class<E> e, final Object value, final String valueMethod) throws InvocationTargetException, NoSuchMethodException, IllegalAccessException {
        E[] values = (E[]) e.getMethod("values").invoke(null);
        for (E o : values) {
            Method method = o.getClass().getMethod(valueMethod);
            Object objValue = method.invoke(o);
            if ((objValue == null && value == null) || (objValue != null && value != null && objValue.equals(value))) {
                return o;
            }
        }
        throw new IllegalStateException("Enum nao encontrado: " + e + " : " + value);
    }

    /**
     * Devolve lista de tarefas.
     * @return lista de tarefas.
     * @throws CouldNotSyncDataException caso nao consiga trazer dados.
     */
    private List<Row> getJiraTasks() throws CouldNotSyncDataException {
        try {
            StringBuilder logins = new StringBuilder();

            Iterator<Map.Entry<String, Participant>> it = participantsCache.entrySet().iterator();
            logins.append("'" + it.next().getKey() + "'");
            while (it.hasNext()) {
                logins.append(", '" + it.next().getKey() + "'");
            }

            StringBuilder sb = new StringBuilder();
            sb.append(" SELECT");
            sb.append("     t.id            AS " + JIRA_ID_ALIAS + ",");
            sb.append("     t.pkey          AS " + JIRA_TASK_KEY_ALIAS + ",");
            sb.append("     t.priority      AS " + PRIORITY_ALIAS + ",");
            sb.append("     cv1.stringvalue AS " + SEVERITY_ALIAS + ",");
            sb.append("     cv2.stringvalue AS " + COMPLEXITY_ALIAS + ",");
            sb.append("     c.author        AS " + USER_LOGIN_ALIAS + ",");
            sb.append("     c.created       AS " + TASK_DATE_ALIAS);
            sb.append(" FROM");
            sb.append("     jiraissue   t INNER JOIN");
            sb.append("     changegroup c ON (t.id = c.issueid) INNER JOIN");
            sb.append("     changeitem  i ON (c.id = i.groupid) LEFT JOIN");
            sb.append("     customfieldvalue cv1 ON (t.id = cv1.issue AND cv1.customfield = '" + CF_SEVERIDADE + "') LEFT JOIN");
            sb.append("     customfieldvalue cv2 ON (t.id = cv2.issue AND cv2.customfield = '" + CF_COMPLEXIDADE + "')");
            sb.append(" WHERE");
            sb.append("     t.project       = '" + PROJECT_ID_BWAM + "' AND");
            sb.append("     t.issuestatus  IN (" + STATUSES_QUE_IMPORTAM + ") AND");
            sb.append("     c.author       IN (" + logins.toString() + ") AND");
            sb.append("     i.oldvalue      = '" + STATUS_EM_DESENV + "' AND");
            sb.append("     i.newvalue      = '" + STATUS_AG_LIBER + "' AND");
            sb.append("     i.field         = '" + ITEM_COLNAME_STATUS + "'");

            return JiraGateway.executeQuery(sb.toString(), JIRA_ID_ALIAS, JIRA_TASK_KEY_ALIAS, PRIORITY_ALIAS, SEVERITY_ALIAS, COMPLEXITY_ALIAS, USER_LOGIN_ALIAS, TASK_DATE_ALIAS);
        } catch (SQLException e) {
            throw new CouldNotSyncDataException(e);
        }
    }
}
