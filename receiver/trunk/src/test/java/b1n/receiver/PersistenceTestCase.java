package b1n.receiver;

import junit.framework.TestCase;

import org.b1n.framework.persistence.JpaUtil;

/**
 * @author Marcio Ribeiro
 * @date Jan 20, 2008
 */
public class PersistenceTestCase extends TestCase {
    public PersistenceTestCase() {
        // do nothing
    }

    public PersistenceTestCase(String arg) {
        super(arg);
    }

    @Override
    protected void setUp() throws Exception {
        super.setUp();
        JpaUtil.getSession();
    }

    @Override
    protected void tearDown() throws Exception {
        try {
            JpaUtil.getSession().getTransaction().setRollbackOnly();
            JpaUtil.closeSession();
        } finally {
            super.tearDown();
        }
    }
}
