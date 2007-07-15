package b1n.framework.persistence.test;

/**
 * @created Jul 15, 2007
 * @author Marcio Ribeiro (mmr)
 */
public abstract class AbstractTestDbServer implements TestDbServer {

    private String databaseName;

    private String userName;

    private String password;

    private String url;

    public AbstractTestDbServer(String databaseName, String userName, String password, String url) {
        this.databaseName = databaseName;
        this.userName = userName;
        this.password = password;
        this.url = url;
    }

    public String getDatabaseName() {
        return databaseName;
    }

    public void setDatabaseName(String databaseName) {
        this.databaseName = databaseName;
    }

    public String getPassword() {
        return password;
    }

    public void setPassword(String password) {
        this.password = password;
    }

    public String getUrl() {
        return url;
    }

    public void setUrl(String url) {
        this.url = url;
    }

    public String getUserName() {
        return userName;
    }

    public void setUserName(String userName) {
        this.userName = userName;
    }
}