package b1n.framework.persistence.test;

/**
 * @created Jul 15, 2007
 * @author Marcio Ribeiro (mmr)
 */
public interface TestDbServer {
    void start();

    void stop();

    String getUserName();

    String getPassword();

    String getUrl();

    void setUserName(String userName);

    void setPassword(String password);

    void setUrl(String url);
}