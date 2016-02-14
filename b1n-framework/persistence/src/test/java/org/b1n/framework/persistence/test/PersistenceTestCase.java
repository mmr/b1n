package org.b1n.framework.persistence.test;

import junit.framework.TestCase;

import org.b1n.framework.persistence.JpaUtil;

/**
 * @author Marcio Ribeiro (mmr)
 * @created Mar 28, 2007
 */
public abstract class PersistenceTestCase extends TestCase {

    /**
     * Configuracao persistencia.
     */
    static final Config CONFIG;

    static {
        // XXX (mmr) : fixo hsql, permitir escolher de fora
        CONFIG = new HsqlConfig();
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
        JpaUtil.getSession(CONFIG.getEmfName(), CONFIG.getEmfConfigOverrides());
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