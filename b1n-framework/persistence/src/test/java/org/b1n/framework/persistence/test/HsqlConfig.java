package org.b1n.framework.persistence.test;

import java.util.HashMap;
import java.util.Map;

import org.hsqldb.Server;

/**
 * @author Marcio Ribeiro
 * @date Jan 17, 2009
 */
public class HsqlConfig implements Config {
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
     * Para servidor hsql.
     * @throws Throwable throwable.
     */
    @Override
    protected void finalize() throws Throwable {
        SERVER.stop();
    }

    /**
     * @return config.
     */
    public Map<String, String> getEmfConfigOverrides() {
        return HSQL_CONFIG_OVERRIDES;
    }

    /**
     * @return emf name.
     */
    public String getEmfName() {
        return DEFAULT_EMF_NAME;
    }
}
