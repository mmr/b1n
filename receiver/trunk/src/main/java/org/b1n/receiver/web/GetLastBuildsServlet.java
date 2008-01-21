package org.b1n.receiver.web;

import java.io.IOException;
import java.io.PrintWriter;
import java.text.DateFormat;
import java.text.NumberFormat;
import java.text.SimpleDateFormat;
import java.util.Date;
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
        resp.setContentType("text/html");
        PrintWriter writer = new PrintWriter(resp.getOutputStream());
        try {
            writer.print("<html><head><title>Build Stats</title><link rel='stylesheet' href='css/tmp.css'/></head><body>");
            writer.print("<h1>Build Stats!</h1><hr/>");
            writer.print("<a name='TOC'>&nbsp;</a>");
            writer.print("<center><table class='toc'>");
            for (Build build : builds) {
                writer.print("<tr><td><a href='#P" + build.getId() + "'>" + build.getProjectName() + " (" + build.getUserName() + ") </a></td>");
                writer.print("<td>" + showDate(build.getStartTime()) + "</td></tr>");
            }
            writer.print("</table></center>");
            writer.print("<hr/>");

            for (Build build : builds) {
                writer.print("<table class='build'>");
                writer.print("<tr class='projectName'><td>");
                writer.print("<a name='P" + build.getId() + "'>" + build.getProjectName() + " " + build.getVersion() + "</a>");
                writer.print("</tr>");

                writer.print("<tr><td>Build Info:");
                writer.print("<table class='userData'><tr>");
                writer.print("<td class='field'>User: " + build.getUserName() + "</td>");
                writer.print("<td class='field'>Host: " + build.getHostName() + "</td>");
                writer.print("<td class='field'>Start: " + showDate(build.getStartTime()) + "</td>");
                writer.print("<td class='field'>End: " + showDate(build.getEndTime()) + "</td>");
                writer.print("<td class='field'>Time: " + showDelta(build.getTimeDelta()) + "</td>");
                writer.print("</tr></table>");
                writer.print("</td></tr>");

                if (!build.getModules().isEmpty()) {
                    writer.print("<td>Modules:");
                    writer.print("<table class='modules'>");
                    for (Module module : build.getModules()) {
                        writer.write("<tr><td class='moduleTitle' colspan='3'>" + module.getGroupId() + "/" + module.getArtifactId() + " " + module.getVersion() + "</td></tr>");
                        writer.write("<tr><td class='start'>Start: " + showDate(module.getStartTime()) + "</td>");
                        writer.write("<td class='end'>End: " + showDate(module.getEndTime()) + "</td>");
                        writer.write("<td class='time'>Time: " + showDelta(module.getTimeDelta()) + "</td></tr>");
                    }
                    writer.print("</table>");
                    writer.print("</td>");
                }
                writer.write("</tr></table>");
                writer.print("<div align='right'><a href='#TOC'>Toc</a></div>");
            }
            writer.print("</body></html>");
        } finally {
            writer.close();
            JpaUtil.closeSession();
        }
    }

    private String showDate(Date date) {
        DateFormat format = new SimpleDateFormat("dd/MM/yy HH:mm:ss");
        return format.format(date);
    }

    private String showDelta(long milisec) {
        if (milisec == 0) {
            return null;
        }
        NumberFormat nf = NumberFormat.getInstance();
        nf.setMinimumIntegerDigits(2);
        int sec = (int) (milisec / 1000);
        return nf.format(sec / 60) + ":" + nf.format(sec % 60);
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
