package org.b1n.framework.persistence.test;

import java.util.Set;

import junit.framework.Test;
import junit.framework.TestCase;
import junit.framework.TestSuite;

import org.b1n.framework.persistence.Entity;
import org.b1n.framework.persistence.JpaUtil;
import org.hibernate.Session;
import org.hibernate.SessionFactory;

/**
 * Testa entidades.
 * @author Marcio Ribeiro (mmr)
 * @created Dec 13, 2008
 */
public abstract class EntitiesTestCase extends PersistenceTestCase {

    /**
     * @return suite.
     */
    public static Test suite() {
        return new EntitiesTestSuite();
    }

    /**
     * @author Marcio Ribeiro (mmr)
     * @created Dec 13, 2008
     */
    private static class EntitiesTestSuite extends TestSuite {
        /**
         * Construtor.
         */
        @SuppressWarnings("unchecked")
        public EntitiesTestSuite() {
            Session session = (Session) JpaUtil.getSession(PersistenceTestCase.CONFIG.getEmfName(), PersistenceTestCase.CONFIG.getEmfConfigOverrides()).getDelegate();
            SessionFactory sf = session.getSessionFactory();
            Set<String> set = sf.getAllClassMetadata().keySet();
            for (final String s : set) {
                this.addTest(new TestCase(s) {
                    @Override
                    protected void runTest() throws Throwable {
                        EntityTester.testEntity((Class<Entity>) Class.forName(s));
                    }
                });
            }
        }
    }
}
