package org.b1n.receiver.web;

import java.io.IOException;
import java.io.PrintWriter;

import javax.servlet.ServletException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

/**
 * @author Marcio Ribeiro
 * @date Jan 21, 2008
 */
public class GetLastBuildsServlet extends HttpServlet {
    @Override
    protected void doGet(HttpServletRequest req, HttpServletResponse resp) throws ServletException, IOException {
        this.doPost(req, resp);
    }

    @Override
    protected void doPost(HttpServletRequest req, HttpServletResponse resp) throws ServletException, IOException {
        resp.setContentType("text/html");
        PrintWriter writer = new PrintWriter(resp.getOutputStream());
        try {
            String url = "/" + req.getContextPath();
            writer.print("<html><head><title>Build Stats</title><link rel='stylesheet' href='css/tmp.css'/></head><body>");
            writer.print("<h1>Build Stats!</h1><hr/>");
            writer.print("O Build Stats mudou de lugar!<br />Acesse: <a href='" + url + "'>" + url + "</a>");
            writer.print("</body></html>");
        } finally {
            writer.close();
        }
    }
}
