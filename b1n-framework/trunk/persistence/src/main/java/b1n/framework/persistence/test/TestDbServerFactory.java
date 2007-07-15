package b1n.framework.persistence.test;

import java.lang.reflect.Constructor;
import java.lang.reflect.InvocationTargetException;

import org.hibernate.cfg.Configuration;

/**
 * @created Jul 15, 2007
 * @author Marcio Ribeiro (mmr)
 */
public class TestDbServerFactory {
    public TestDbServer createTestDbServer() {
        try {
            // Session hibernateSession = (Session) JpaUtil.getSession();
            // Connection conn = hibernateSession.connection();
            // DatabaseMetaData connMetaData = conn.getMetaData();
            // String dbName = connMetaData.getDatabaseProductName().toLowerCase();
            // dbName = dbName.substring(0, 1).toUpperCase() + dbName.substring(1);
            // Class testDbServerClass = Class.forName(dbName + "TestDbServer");
            // Constructor constructor = testDbServerClass.getDeclaredConstructor(String.class, String.class, String.class, String.class);
            // return (TestDbServer) constructor.newInstance(connMetaData.getUserName(), connMetaData.getUserName(), null, connMetaData.getURL());

            Configuration conf = new Configuration();
            conf.configure("hibernate.cfg.xml");
            String url = conf.getProperty("hibernate.connection.url");
            String dbName = url.substring(url.lastIndexOf("/") + 1);
            String userName = conf.getProperty("hibernate.connection.username");
            String password = conf.getProperty("hibernate.connection.password");
            Class testDbServerClass = getTestDbServerClass(conf);
            Constructor constructor = testDbServerClass.getDeclaredConstructor(String.class, String.class, String.class, String.class);
            return (TestDbServer) constructor.newInstance(dbName, userName, password, url);
        } catch (ClassNotFoundException e) {
            // TODO Auto-generated catch block
            e.printStackTrace();
        } catch (NoSuchMethodException e) {
            // TODO Auto-generated catch block
            e.printStackTrace();
        } catch (InstantiationException e) {
            // TODO Auto-generated catch block
            e.printStackTrace();
        } catch (IllegalAccessException e) {
            // TODO Auto-generated catch block
            e.printStackTrace();
        } catch (InvocationTargetException e) {
            // TODO Auto-generated catch block
            e.printStackTrace();
        }
        throw new RuntimeException("Could not create testDbServer");
    }

    private Class getTestDbServerClass(Configuration conf) throws ClassNotFoundException {
        String dbName = conf.getProperty("hibernate.dialect").replace("Dialect", "");
        dbName = dbName.substring(dbName.lastIndexOf('.') + 1).toLowerCase();
        dbName = this.getClass().getPackage().getName() + "." + dbName.substring(0, 1).toUpperCase() + dbName.substring(1) + "TestDbServer";
        return Class.forName(dbName);
    }
}