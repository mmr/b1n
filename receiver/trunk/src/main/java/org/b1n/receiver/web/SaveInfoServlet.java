package org.b1n.receiver.web;

import java.io.IOException;
import java.io.PrintWriter;
import java.util.Date;

import javax.servlet.ServletException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import org.b1n.framework.persistence.DaoLocator;
import org.b1n.framework.persistence.EntityNotFoundException;
import org.b1n.receiver.domain.Build;
import org.b1n.receiver.domain.BuildDao;
import org.b1n.receiver.domain.Module;
import org.b1n.receiver.domain.ModuleDao;

/**
 * Salva dados.
 * @author Marcio Ribeiro
 * @date Jan 20, 2008
 */
public class SaveInfoServlet extends HttpServlet {
    private static final String PARAM_ACTION = "action";
    private static final String START_BUILD_ACTION = "startBuild";
    private static final String START_MODULE_ACTION = "startModule";
    private static final String END_BUILD_ACTION = "endBuild";
    private static final String END_MODULE_ACTION = "endModule";

    private static final String PARAM_BUILD_ID = "buildId";
    private static final String PARAM_MODULE_ID = "moduleId";
    private static final String PARAM_PROJECT = "project";
    private static final String PARAM_VERSION = "version";
    private static final String PARAM_GROUP_ID = "groupId";
    private static final String PARAM_ARTIFACT_ID = "artifactId";
    private static final String PARAM_HOSTNAME = "hostName";
    private static final String PARAM_HOSTIP = "hostIp";
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
        String action = req.getParameter(PARAM_ACTION);

        if (action == null) {
            return;
        }

        if (action.equals(START_BUILD_ACTION)) {
            long buildId = saveStartBuild(req);
            sendId(resp, buildId);
        } else if (action.equals(START_MODULE_ACTION)) {
            long moduleId = saveStartModule(req);
            sendId(resp, moduleId);
        } else if (action.equals(END_MODULE_ACTION)) {
            saveEndModule(req);
        } else if (action.equals(END_BUILD_ACTION)) {
            saveEndBuild(req);
        }
    }

    private void sendId(HttpServletResponse resp, long id) throws IOException {
        PrintWriter writer = new PrintWriter(resp.getOutputStream());
        try {
            writer.print(id);
        } finally {
            writer.close();
        }
    }

    private void saveEndBuild(HttpServletRequest req) {
        String strBuildId = req.getParameter(PARAM_BUILD_ID);

        long buildId = 0;
        try {
            buildId = Long.parseLong(strBuildId);
        } catch (NumberFormatException e) {
            // TODO (mmr) : tratar erro
        }

        BuildDao buildDao = DaoLocator.getDao(Build.class);
        Build build = null;
        try {
            build = buildDao.findById(buildId);
        } catch (EntityNotFoundException e) {
            // TODO (mmr) : tratar erro
        }
        build.setEndTime(new Date());
        build.save();
    }

    private void saveEndModule(HttpServletRequest req) {
        String strModuleId = req.getParameter(PARAM_MODULE_ID);

        long moduleId = 0;
        try {
            moduleId = Long.parseLong(strModuleId);
        } catch (NumberFormatException e) {
            // TODO (mmr) : tratar erro
        }

        ModuleDao moduleDao = DaoLocator.getDao(Module.class);
        Module module = null;
        try {
            module = moduleDao.findById(moduleId);
        } catch (EntityNotFoundException e) {
            // TODO (mmr) : tratar erro
        }
        module.setEndTime(new Date());
        module.save();
    }

    private long saveStartModule(HttpServletRequest req) {
        // TODO (mmr) : tratar parametros nulos
        String strBuildId = req.getParameter(PARAM_BUILD_ID);
        String version = req.getParameter(PARAM_VERSION);
        String groupId = req.getParameter(PARAM_GROUP_ID);
        String artifactId = req.getParameter(PARAM_ARTIFACT_ID);

        long buildId = 0;
        try {
            buildId = Long.parseLong(strBuildId);
        } catch (NumberFormatException e) {
            // TODO (mmr) : tratar erro
        }

        BuildDao buildDao = DaoLocator.getDao(Build.class);
        Build build = null;
        try {
            build = buildDao.findById(buildId);
        } catch (EntityNotFoundException e) {
            // TODO (mmr) : tratar erro
        }

        Module module = new Module();
        module.setBuild(build);
        module.setVersion(version);
        module.setGroupId(groupId);
        module.setArtifactId(artifactId);
        module.setStartTime(new Date());

        build.addModule(module);
        build.save();
        return module.getId();
    }

    private long saveStartBuild(HttpServletRequest req) {
        // TODO (mmr) : tratar parametros nulos
        String project = req.getParameter(PARAM_PROJECT);
        String version = req.getParameter(PARAM_VERSION);
        String groupId = req.getParameter(PARAM_GROUP_ID);
        String artifactId = req.getParameter(PARAM_ARTIFACT_ID);
        String hostName = req.getParameter(PARAM_HOSTNAME);
        String hostIp = req.getParameter(PARAM_HOSTIP);
        String userName = req.getParameter(PARAM_USERNAME);
        String jvm = req.getParameter(PARAM_JVM);
        String encoding = req.getParameter(PARAM_ENCODING);

        Build build = new Build();
        build.setProjectName(project);
        build.setVersion(version);
        build.setGroupId(groupId);
        build.setArtifactId(artifactId);
        build.setHostName(hostName);
        build.setHostIp(hostIp);
        build.setHostRequestIp(req.getRemoteAddr());
        build.setStartTime(new Date());
        build.setUserName(userName);
        build.setJvm(jvm);
        build.setEncoding(encoding);
        build.save();
        return build.getId();
    }
}
