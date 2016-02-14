package org.b1n.jirator.web.servlet;

import java.io.IOException;

import javax.servlet.ServletException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import org.b1n.framework.persistence.JpaUtil;
import org.b1n.jirator.jira.CouldNotSyncDataException;
import org.b1n.jirator.jira.ParticipantSyncer;
import org.b1n.jirator.jira.TaskSyncer;

/**
 * Servlet que sincroniza entidades com contra parte no Jira.
 * @author Marcio Ribeiro
 * @date May 5, 2008
 */
public class SyncServlet extends HttpServlet {

    /**
     * @param req request http.
     * @param resp response.
     * @throws ServletException servlet exception.
     * @throws IOException ioexception.
     */
    @Override
    protected void service(final HttpServletRequest req, final HttpServletResponse resp) throws ServletException, IOException {
        try {
            JpaUtil.getSession();

            // Sincroniza lista de usuarios
            new ParticipantSyncer().syncData();

            // Sincroniza tarefas
            new TaskSyncer().syncData();
        } catch (CouldNotSyncDataException e) {
            throw new ServletException(e);
        } finally {
            JpaUtil.closeSession();
        }
    }
}
