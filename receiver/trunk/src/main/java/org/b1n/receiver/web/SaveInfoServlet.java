package org.b1n.receiver.web;

import java.io.IOException;

import javax.servlet.ServletException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import org.b1n.receiver.domain.Info;


/**
 * Salva dados.
 * @author Marcio Ribeiro
 * @date Jan 20, 2008
 */
public class SaveInfoServlet extends HttpServlet {
    private static final String PARAM_ACTION = "action";
    private static final String PARAM_PROJECT = "project";
    private static final String PARAM_VERSION = "version";
    private static final String PARAM_GROUP_ID = "groupId";
    private static final String PARAM_ARTIFACT_ID = "artifactId";
    private static final String PARAM_HOSTNAME = "hostName";
    private static final String PARAM_USERNAME = "userName";
    private static final String PARAM_JVM = "jvm";
    private static final String PARAM_ENCODING = "encoding";

    /**
     * @param req requisicao.
     * @param resp resposta.
     * @throws ServletException caso algo de inesperado ocorra.
     * @throws IOException caso algo de inesperado ocorra.
     */
    @Override
    protected void doGet(HttpServletRequest req, HttpServletResponse resp) throws ServletException, IOException {
        this.doPost(req, resp);
    }

    /**
     * Salva dados.
     * @param req requisicao.
     * @param resp resposta.
     * @throws ServletException caso algo de inesperado ocorra.
     * @throws IOException caso algo de inesperado ocorra.
     */
    @Override
    protected void doPost(HttpServletRequest req, HttpServletResponse resp) throws ServletException, IOException {
        Info info = new Info();
        info.setAction(req.getParameter(PARAM_ACTION));
        info.setProjectName(req.getParameter(PARAM_PROJECT));
        info.setVersion(req.getParameter(PARAM_VERSION));
        info.setGroupId(req.getParameter(PARAM_GROUP_ID));
        info.setArtifactId(req.getParameter(PARAM_ARTIFACT_ID));
        info.setHostName(req.getParameter(PARAM_HOSTNAME));
        info.setUserName(req.getParameter(PARAM_USERNAME));
        info.setJvm(req.getParameter(PARAM_JVM));
        info.setEncoding(req.getParameter(PARAM_ENCODING));
        info.save();
    }
}
