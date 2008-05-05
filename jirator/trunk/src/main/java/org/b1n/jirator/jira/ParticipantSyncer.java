package org.b1n.jirator.jira;

import java.math.BigDecimal;
import java.sql.SQLException;
import java.util.List;

import org.b1n.framework.persistence.DaoLocator;
import org.b1n.framework.persistence.EntityNotFoundException;
import org.b1n.jirator.domain.Participant;
import org.b1n.jirator.domain.ParticipantDao;

/**
 * Sincroniza com usuarios do jira.
 * @author Marcio Ribeiro
 * @date May 5, 2008
 */
public class ParticipantSyncer implements JiraSyncer<Participant> {
    // XXX (mmr) : nao deixar nome de grupo hardcoded
    private static final String GROUP_NAME = "bwam-manutencao";

    private static final String FULL_NAME_PROPERTY_KEY = "fullName";

    private static final String JIRA_LOGIN_ALIAS = "jiraLogin";

    private static final String JIRA_ID_ALIAS = "jiraId";

    private static final String JIRA_USERNAME_ALIAS = "jiraUserName";

    /**
     * Sincroniza lista de usuarios com usuarios do jira.
     * @throws CouldNotSyncDataException caso nao consiga trazer dados do jira.
     */
    public void syncData() throws CouldNotSyncDataException {
        ParticipantDao dao = DaoLocator.getDao(Participant.class);
        List<Row> jiraUsers = getJiraUsers();
        for (Row row : jiraUsers) {
            Long jiraId = ((BigDecimal) row.get(JIRA_ID_ALIAS)).longValue();
            String jiraLogin = (String) row.get(JIRA_LOGIN_ALIAS);
            String userName = (String) row.get(JIRA_USERNAME_ALIAS);
            try {
                dao.findByJiraId(jiraId);
            } catch (EntityNotFoundException e) {
                // Nao encontrado, deve criar usuario
                Participant p = new Participant();
                p.setJiraId(jiraId);
                p.setJiraLogin(jiraLogin);
                p.setUserName(userName);
                p.save();
            }
        }
    }

    /**
     * @return lista de usuarios do jira.
     * @throws CouldNotSyncDataException caso nao consiga trazer dados.
     */
    private List<Row> getJiraUsers() throws CouldNotSyncDataException {
        try {
            StringBuilder sb = new StringBuilder();
            sb.append(" SELECT");
            sb.append("     u.id            AS " + JIRA_ID_ALIAS + ",");
            sb.append("     u.username      AS " + JIRA_LOGIN_ALIAS + ",");
            sb.append("     v.propertyvalue AS " + JIRA_USERNAME_ALIAS);
            sb.append(" FROM");
            sb.append("     propertyentry   e NATURAL JOIN");
            sb.append("     propertystring  v INNER JOIN");
            sb.append("     userbase        u ON (e.entity_id = u.id) INNER JOIN");
            sb.append("     membershipbase  g ON (u.username = g.user_name)");
            sb.append(" WHERE");
            sb.append("     g.group_name    = '" + GROUP_NAME + "' AND");
            sb.append("     e.property_key  = '" + FULL_NAME_PROPERTY_KEY + "'");

            return JiraGateway.executeQuery(sb.toString(), JIRA_ID_ALIAS, JIRA_LOGIN_ALIAS, JIRA_USERNAME_ALIAS);
        } catch (SQLException e) {
            throw new CouldNotSyncDataException(e);
        }
    }
}
