package org.b1n.jirator.jira;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;
import java.util.ArrayList;
import java.util.List;

/**
 * @author Marcio Ribeiro
 * @date May 3, 2008
 */
public final class JiraGateway {
    private static final ThreadLocal<Connection> CONNECTION = new ThreadLocal<Connection>();

    /**
     * Classe utilitaria, nao deve ser instanciada.
     */
    private JiraGateway() {
        // do nothing
    }

    /**
     * Executa query sql e encapsula resutlado em lista de linhas.
     * @param query query.
     * @param columns nome de colunas do resultado.
     * @return lista de linhas do resultado.
     * @throws SQLException caso query falhe.
     */
    public static List<Row> executeQuery(final String query, final String... columns) throws SQLException {
        final List<Row> rows = new ArrayList<Row>();
        Statement st = null;
        ResultSet rs = null;
        try {
            st = getConnection().createStatement();
            rs = st.executeQuery(query);
            while (rs.next()) {
                Row row = new Row();
                for (String col : columns) {
                    row.put(col, rs.getObject(col));
                }
                rows.add(row);
            }
        } finally {
            rs.close();
            st.close();
        }

        return rows;
    }

    /**
     * @return conexao.
     * @throws SQLException caso ocorra erro ao pegar conexao.
     */
    private static Connection getConnection() throws SQLException {
        if (CONNECTION.get() == null) {
            CONNECTION.set(createConnection());
        }
        return CONNECTION.get();
    }

    /**
     * Cria conexao.
     * @return conexao.
     * @throws SQLException caso de errado ocorra.
     */
    private static Connection createConnection() throws SQLException {
        // TODO (mmr) : colocar dados de conexao em arquivo de configuracao
        final String url = "jdbc:postgresql://localhost/jira";
        final String user = "jira";
        final String pass = "jira";
        try {
            Class.forName("org.postgresql.Driver");
            return DriverManager.getConnection(url, user, pass);
        } catch (ClassNotFoundException e) {
            throw new SQLException(e.getMessage());
        }
    }

    /**
     * Fecha conexao.
     * @throws SQLException caso ocorra erro ao fechar conexao.
     */
    public static void closeConnection() throws SQLException {
        final Connection conn = CONNECTION.get();
        if (conn != null && !conn.isClosed()) {
            conn.close();
        }
    }
}
