package org.b1n.jirator.jira;

import java.util.List;

import junit.framework.TestCase;

/**
 * @author Marcio Ribeiro
 * @date May 2, 2008
 */
public class JiraGatewayTest extends TestCase {

    /**
     * Teste de query bem simples.
     * @throws Exception caso algo de errado ocorra.
     */
    public void testSimpleQuery() throws Exception {
        List<Row> rows = JiraGateway.executeQuery("SELECT 1 + 1 AS r", "r");
        assertNotNull(rows);
        assertEquals(1, rows.size());
        assertEquals(2, rows.get(0).get("r"));
    }

    /**
     * Testa query que encontra nome do usuario no jira.
     * @throws Exception caso algo de errado ocorra.
     */
    public void testUserNameQuery() throws Exception {
        StringBuilder sb = new StringBuilder();
        sb.append(" SELECT v.propertyvalue AS name");
        sb.append(" FROM");
        sb.append("     propertyentry  e NATURAL JOIN");
        sb.append("     propertystring v INNER JOIN");
        sb.append("     userbase u ON (e.entity_id = u.id)");
        sb.append(" WHERE");
        sb.append("     u.username = 'mmr' AND");
        sb.append("     e.property_key = 'fullName'");

        List<Row> rows = JiraGateway.executeQuery(sb.toString(), "name");
        assertNotNull(rows);
        assertEquals(1, rows.size());
        assertEquals("Marcio Ribeiro", rows.get(0).get("name"));
    }

    /**
     * Finalize.
     * @throws Throwable e.
     */
    @Override
    protected void finalize() throws Throwable {
        super.finalize();
        JiraGateway.closeConnection();
    }
}
