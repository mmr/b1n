package b1n.framework.persistence.test;

import java.io.PrintWriter;

import org.hsqldb.Server;
import org.hsqldb.ServerConstants;

/**
 * @created Jul 15, 2007
 * @author Marcio Ribeiro (mmr)
 */
public class HsqlTestDbServer extends AbstractTestDbServer {

    private static Server hsqlServerInstance;

    public HsqlTestDbServer(String databaseName, String userName, String password, String url) {
        super(databaseName, userName, password, url);
    }

    private static Server getHsqlServerInstance() {
        if (hsqlServerInstance == null) {
            hsqlServerInstance = new Server();
            hsqlServerInstance.setLogWriter(null);
            hsqlServerInstance.setErrWriter(new PrintWriter(System.err));
        }
        return hsqlServerInstance;
    }

    public void start() {
        if (isRunning()) {
            return;
        }
        getHsqlServerInstance().putPropertiesFromString("database.0=mem:" + getDatabaseName());
        getHsqlServerInstance().putPropertiesFromString("dbname.0=" + getDatabaseName());
        getHsqlServerInstance().start();
    }

    public void stop() {
        getHsqlServerInstance().stop();
    }

    private boolean isRunning() {
        int state = getHsqlServerInstance().getState();
        return state == ServerConstants.SERVER_STATE_ONLINE || state == ServerConstants.SERVER_STATE_OPENING;
    }
}