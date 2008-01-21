package b1n.receiver.domain;

import org.b1n.framework.persistence.DaoLocator;
import org.b1n.framework.persistence.EntityNotFoundException;
import org.b1n.receiver.domain.Info;
import org.b1n.receiver.domain.InfoDao;

import b1n.receiver.PersistenceTestCase;

/**
 * @author Marcio Ribeiro
 * @date Jan 20, 2008
 */
public class InfoTest extends PersistenceTestCase {
    private static final String ACTION = "test";
    private static final String PROJECT = "receiver";
    private static final String VERSION = "1.0";
    private static final String GROUP_ID = "b1n";
    private static final String ARTIFACT_ID = "b1n-receiver";
    private static final String HOSTNAME = "spike";
    private static final String USERNAME = "mmr";
    private static final String JVM = "1.5.0_14";
    private static final String ENCODING = "ISO-8859-1";

    public void testPersistence() {
        Info info = new Info();
        info.setAction(ACTION);
        info.setProjectName(PROJECT);
        info.setVersion(VERSION);
        info.setGroupId(GROUP_ID);
        info.setArtifactId(ARTIFACT_ID);
        info.setHostName(HOSTNAME);
        info.setUserName(USERNAME);
        info.setJvm(JVM);
        info.setEncoding(ENCODING);
        info.save();
        long id = info.getId();

        InfoDao infoDao = DaoLocator.getDao(Info.class);
        try {
            Info loadedInfo = infoDao.findById(id);
            assertEquals(info.getAction(), loadedInfo.getAction());
            assertEquals(info.getProjectName(), loadedInfo.getProjectName());
            assertEquals(info.getVersion(), loadedInfo.getVersion());
            assertEquals(info.getGroupId(), loadedInfo.getGroupId());
            assertEquals(info.getArtifactId(), loadedInfo.getArtifactId());
            assertEquals(info.getHostName(), loadedInfo.getHostName());
            assertEquals(info.getUserName(), loadedInfo.getUserName());
            assertEquals(info.getJvm(), loadedInfo.getJvm());
            assertEquals(info.getEncoding(), loadedInfo.getEncoding());
        } catch (EntityNotFoundException e) {
            fail(e.getMessage());
        }

        info.remove();
        try {
            infoDao.findById(id);
            fail("Nao removeu!");
        } catch (EntityNotFoundException e) {
            // ok!
        }
    }
}
