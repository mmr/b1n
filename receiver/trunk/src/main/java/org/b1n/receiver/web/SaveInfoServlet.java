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
import org.b1n.receiver.domain.Host;
import org.b1n.receiver.domain.HostDao;
import org.b1n.receiver.domain.ModuleBuild;
import org.b1n.receiver.domain.ModuleBuildDao;
import org.b1n.receiver.domain.Project;
import org.b1n.receiver.domain.ProjectBuild;
import org.b1n.receiver.domain.ProjectBuildDao;
import org.b1n.receiver.domain.ProjectDao;
import org.b1n.receiver.domain.User;
import org.b1n.receiver.domain.UserDao;

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
    private static final String PARAM_PROJECT_NAME = "project";
    private static final String PARAM_VERSION = "version";
    private static final String PARAM_GROUP_ID = "groupId";
    private static final String PARAM_ARTIFACT_ID = "artifactId";
    private static final String PARAM_HOSTNAME = "hostName";
    private static final String PARAM_HOSTIP = "hostIp";
    private static final String PARAM_USERNAME = "userName";
    private static final String PARAM_JVM = "jvm";
    private static final String PARAM_ENCODING = "encoding";
    private static final String PARAM_OS = "os";

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

        try {
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
        } catch (CouldNotSaveException e) {
            throw new ServletException(e.getCause());
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

    private void saveEndBuild(HttpServletRequest req) throws CouldNotSaveException {
        String strBuildId = req.getParameter(PARAM_BUILD_ID);

        long buildId = 0;
        try {
            buildId = Long.parseLong(strBuildId);
        } catch (NumberFormatException e) {
            throw new CouldNotSaveException(e);
        }

        ProjectBuildDao pbDao = DaoLocator.getDao(ProjectBuild.class);
        Build build = null;
        try {
            build = pbDao.findById(buildId);
        } catch (EntityNotFoundException e) {
            throw new CouldNotSaveException(e);
        }
        build.setEndTime(new Date());
        build.save();
    }

    private void saveEndModule(HttpServletRequest req) throws CouldNotSaveException {
        String strModuleId = req.getParameter(PARAM_MODULE_ID);

        long moduleId = 0;
        try {
            moduleId = Long.parseLong(strModuleId);
        } catch (NumberFormatException e) {
            throw new CouldNotSaveException(e);
        }

        ModuleBuildDao moduleDao = DaoLocator.getDao(ModuleBuild.class);
        ModuleBuild module = null;
        try {
            module = moduleDao.findById(moduleId);
        } catch (EntityNotFoundException e) {
            throw new CouldNotSaveException(e);
        }
        module.setEndTime(new Date());
        module.save();
    }

    private long saveStartModule(HttpServletRequest req) throws CouldNotSaveException {
        String strBuildId = req.getParameter(PARAM_BUILD_ID);
        String groupId = req.getParameter(PARAM_GROUP_ID);
        String artifactId = req.getParameter(PARAM_ARTIFACT_ID);
        String version = req.getParameter(PARAM_VERSION);
        String projectName = req.getParameter(PARAM_PROJECT_NAME);

        if (strBuildId == null || groupId == null || artifactId == null || version == null) {
            throw new CouldNotSaveException("Missing args");
        }

        long buildId = 0;
        try {
            buildId = Long.parseLong(strBuildId);
        } catch (NumberFormatException e) {
            throw new CouldNotSaveException(e);
        }

        ProjectBuildDao pbDao = DaoLocator.getDao(ProjectBuild.class);
        ProjectBuild pb = null;
        try {
            pb = pbDao.findById(buildId);
        } catch (EntityNotFoundException e) {
            throw new CouldNotSaveException(e);
        }

        Project project = getProject(projectName, version, groupId, artifactId);

        ModuleBuild mb = new ModuleBuild();
        mb.setProject(project);
        mb.setStartTime(new Date());

        pb.addModule(mb);
        pb.save();
        return mb.getId();
    }

    private long saveStartBuild(HttpServletRequest req) throws CouldNotSaveException {
        String projectName = req.getParameter(PARAM_PROJECT_NAME);
        String version = req.getParameter(PARAM_VERSION);
        String groupId = req.getParameter(PARAM_GROUP_ID);
        String artifactId = req.getParameter(PARAM_ARTIFACT_ID);
        String userName = req.getParameter(PARAM_USERNAME);
        String hostName = req.getParameter(PARAM_HOSTNAME);
        String hostIp = req.getParameter(PARAM_HOSTIP);
        String jvm = req.getParameter(PARAM_JVM);
        String encoding = req.getParameter(PARAM_ENCODING);
        String os = req.getParameter(PARAM_OS);

        if (groupId == null || artifactId == null || version == null || userName == null || hostName == null) {
            throw new CouldNotSaveException("Missing args");
        }

        Project project = getProject(projectName, version, groupId, artifactId);
        User user = getUser(userName);
        Host host = getHost(hostName, hostIp, jvm, encoding, os);

        ProjectBuild build = new ProjectBuild();
        build.setUser(user);
        build.setProject(project);
        build.setHost(host);

        build.setStartTime(new Date());
        build.save();
        return build.getId();
    }

    private Host getHost(String hostName, String hostIp, String jvm, String encoding, String os) {
        HostDao hostDao = DaoLocator.getDao(Host.class);
        Host host = null;
        try {
            host = hostDao.findByHostName(hostName);
        } catch (EntityNotFoundException e) {
            host = new Host(hostName, hostIp, os, jvm, encoding);
            host.save();
        }
        return host;
    }

    private Project getProject(String projectName, String version, String groupId, String artifactId) {
        Project project = null;
        try {
            ProjectDao projectDao = DaoLocator.getDao(Project.class);
            project = projectDao.findByKey(groupId, artifactId, version);
        } catch (EntityNotFoundException e) {
            project = new Project(groupId, artifactId, version, projectName);
            project.save();
        }
        return project;
    }

    private User getUser(String userName) {
        User user = null;
        try {
            UserDao userDao = DaoLocator.getDao(User.class);
            user = userDao.findByUserName(userName);
        } catch (EntityNotFoundException e) {
            user = new User(userName);
            user.save();
        }
        return user;
    }
}
