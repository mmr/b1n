package org.b1n.receiver.web;

import java.io.IOException;
import java.io.PrintWriter;
import java.util.List;

import javax.servlet.ServletException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import org.b1n.framework.persistence.DaoLocator;
import org.b1n.framework.persistence.JpaUtil;
import org.b1n.receiver.domain.Build;
import org.b1n.receiver.domain.BuildDao;
import org.b1n.receiver.domain.Module;

/**
 * @author Marcio Ribeiro
 * @date Jan 21, 2008
 */
public class GetLastBuildsServlet extends HttpServlet {
    private static final int MAX_BUILDS_TO_GET = 20;

    private static final int DEFAULT_LIMIT = 10;
    
    @Override
    protected void doGet(HttpServletRequest req, HttpServletResponse resp) throws ServletException, IOException {
        this.doPost(req, resp);
    }

    @Override
    protected void doPost(HttpServletRequest req, HttpServletResponse resp) throws ServletException, IOException {
        int limit = getMaxResults(req.getParameter("limit"));
        int offset = getOffset(req.getParameter("offset"));
        JpaUtil.getSession();
        BuildDao buildDao = DaoLocator.getDao(Build.class);
        List<Build> builds = buildDao.findLastAddedBuilds(limit, offset);

        // Sim, eu sei que nao deveria coloca isso aqui.
        // A view sera refeita
        PrintWriter writer = new PrintWriter(resp.getOutputStream());
        try {
            writer.print("<html><head><title>Build Stats</title><link rel='stylesheet' href='css/tmp.css'/></head><body>");
            writer.print("<h1>Build Stats!</h1><hr/>");
            for (Build build : builds) {
                writer.print("<table class='build'>");
                writer.print("<tr><td colspan='2' class='projectName'>" + build.getProjectName() + " " + build.getVersion() + "</td></tr>");

                writer.print("<tr><td>Build Info:");
                writer.print("<table class='userData'><tr>");
                writer.print("<td class='field'>User: " + build.getUserName() + "</td>");
                writer.print("<td class='field'>Host: " + build.getHostName() + "</td>");
                writer.print("<td class='field'>Start: " + build.getStartTime() + "</td>");
                writer.print("<td class='field'>End: " + build.getEndTime() + "</td>");
                writer.print("</tr></table>");
                writer.print("</td></tr>");

                if (!build.getModules().isEmpty()) {
                    writer.print("<td>Modules:");
                    writer.print("<table class='modules'>");
                    for (Module module : build.getModules()) {
                        writer.write("<tr><td class='moduleTitle' colspan='2'>" + module.getGroupId() + "/" + module.getArtifactId() + " " + module.getVersion() + "</td></tr>");
                        writer.write("<tr class='start'><td>Start</td><td>" + module.getStartTime() + "</td></tr>");
                        writer.write("<tr class='end'><td>End</td><td>" + module.getEndTime() + "</td></tr>");
                    }
                    writer.print("</table>");
                    writer.print("</td>");
                }
                writer.write("</tr></table>");
            }
            writer.print("</body></html>");
        } finally {
            writer.close();
            JpaUtil.closeSession();
        }
    }

    private int getMaxResults(String strLimit) {
        int limit = 0;
        try {
            limit = Integer.parseInt(strLimit);
            if (limit > MAX_BUILDS_TO_GET) {
                limit = MAX_BUILDS_TO_GET;
            }
            if (limit == 0) {
                limit = DEFAULT_LIMIT;
            }
        } catch (NumberFormatException e) {
            limit = MAX_BUILDS_TO_GET;
        }
        return limit;
    }

    private int getOffset(String strOffset) {
        int offset = 0;
        try {
            offset = Integer.parseInt(strOffset);
        } catch (NumberFormatException e) {
            offset = 0;
        }
        return offset;
    }
}
