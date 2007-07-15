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