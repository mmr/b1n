/* Copyright (c) 2007, B1N.ORG
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *     * Redistributions of source code must retain the above copyright
 *       notice, this list of conditions and the following disclaimer.
 *     * Redistributions in binary form must reproduce the above copyright
 *       notice, this list of conditions and the following disclaimer in the
 *       documentation and/or other materials provided with the distribution.
 *     * Neither the name of the B1N.ORG organization nor the
 *       names of its contributors may be used to endorse or promote products
 *       derived from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS "AS IS" AND ANY
 * EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED. IN NO EVENT SHALL B1N.ORG OR ITS CONTRIBUTORS BE LIABLE FOR ANY
 * DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
 * ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 */
package b1n.framework.persistence.test;

import java.lang.reflect.Constructor;
import java.lang.reflect.InvocationTargetException;

import org.hibernate.cfg.Configuration;

/**
 * @created Jul 15, 2007
 * @author Marcio Ribeiro (mmr)
 */
public class TestDbServerFactory {
    public TestDbServer createTestDbServer() throws CouldNotCreateTestDbServerException {
        try {
            Configuration conf = new Configuration();
            conf.configure("hibernate.cfg.xml");
            String url = conf.getProperty("hibernate.connection.url");
            String dbName = url.substring(url.lastIndexOf("/") + 1);
            String userName = conf.getProperty("hibernate.connection.username");
            String password = conf.getProperty("hibernate.connection.password");
            Class testDbServerClass = getTestDbServerClass(conf);
            Constructor constructor = testDbServerClass.getDeclaredConstructor(String.class, String.class, String.class, String.class);
            return (TestDbServer) constructor.newInstance(dbName, userName, password, url);
        } catch (NoSuchMethodException e) {
            throw new CouldNotCreateTestDbServerException(e);
        } catch (InstantiationException e) {
            throw new CouldNotCreateTestDbServerException(e);
        } catch (IllegalAccessException e) {
            throw new CouldNotCreateTestDbServerException(e);
        } catch (InvocationTargetException e) {
            throw new CouldNotCreateTestDbServerException(e);
        }
    }

    private Class getTestDbServerClass(Configuration conf) {
        String dbName = conf.getProperty("hibernate.dialect").replace("Dialect", "");
        dbName = dbName.substring(dbName.lastIndexOf('.') + 1).toLowerCase();
        dbName = this.getClass().getPackage().getName() + "." + dbName.substring(0, 1).toUpperCase() + dbName.substring(1) + "TestDbServer";
        try {
            return Class.forName(dbName);
        } catch (ClassNotFoundException e) {
            return DefaultTestDbServer.class;
        }
    }
}