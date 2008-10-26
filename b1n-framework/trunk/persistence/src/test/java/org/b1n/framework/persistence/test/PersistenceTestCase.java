package org.b1n.framework.persistence.test;

import java.util.HashMap;
import java.util.Map;

import junit.framework.TestCase;

import org.b1n.framework.persistence.JpaUtil;
import org.hsqldb.Server;

/**
 * @author Marcio Ribeiro (mmr)
 * @created Mar 28, 2007
 */
public abstract class PersistenceTestCase extends TestCase {
    private static final Server SERVER;

    private static final Map<String, String> HSQL_CONFIG_OVERRIDES;

    private static final String DEFAULT_EMF_NAME = "b1n";

    static {
        // Start HSQLDB Server programatically
        SERVER = new Server();
        SERVER.putPropertiesFromString("database.0=mem:test");
        SERVER.putPropertiesFromString("dbname.0=test");
        SERVER.start();

        HSQL_CONFIG_OVERRIDES = new HashMap<String, String>();
        HSQL_CONFIG_OVERRIDES.put("hibernate.connection.driver_class", "org.hsqldb.jdbcDriver");
        HSQL_CONFIG_OVERRIDES.put("hibernate.connection.url", "jdbc:hsqldb:hsql://localhost/test");
        HSQL_CONFIG_OVERRIDES.put("hibernate.connection.username", "sa");
        HSQL_CONFIG_OVERRIDES.put("hibernate.connection.password", "");
        HSQL_CONFIG_OVERRIDES.put("hibernate.dialect", "org.hibernate.dialect.HSQLDialect");
    }

    /**
     * Constructor.
     */
    public PersistenceTestCase() {
        // do nothing
    }

    /**
     * Setup.
     * @throws Exception exception.
     */
    @Override
    protected void setUp() throws Exception {
        super.setUp();
        JpaUtil.getSession(getEmfName(), getEmfConfigOverrides());
    }

    /**
     * @return nome de entity manager factory para testes.
     */
    protected String getEmfName() {
        return DEFAULT_EMF_NAME;
    }

    /**
     * Forca testes em HSQL.
     * @return mapa que forca testes em HSQL.
     */
    protected Map<String, String> getEmfConfigOverrides() {
        return HSQL_CONFIG_OVERRIDES;
    }

    /**
     * Tear down.
     * @throws Exception exception.
     */
    @Override
    protected void tearDown() throws Exception {
        try {
            JpaUtil.closeSession();
        } finally {
            super.tearDown();
        }
    }
}