package b1n.receiver.domain;

import javax.persistence.Column;
import javax.persistence.Entity;

import b1n.framework.persistence.SimpleEntity;

/**
 * Dados enviados pelo Informer.
 * @author Marcio Ribeiro
 * @date Jan 20, 2008
 */
@Entity
public class Info extends SimpleEntity {
    @Column(nullable = false)
    private String action;

    @Column(nullable = false)
    private String hostName;

    @Column(nullable = false)
    private String projectName;

    @Column(nullable = false)
    private String groupId;

    @Column(nullable = false)
    private String artifactId;

    @Column(nullable = false)
    private String version;

    private String userName;
    private String encoding;
    private String jvm;

    /**
     * @return a acao.
     */
    public String getAction() {
        return action;
    }

    /**
     * Define a acao.
     * @param action a acao.
     */
    public void setAction(String action) {
        this.action = action;
    }

    /**
     * @return nome do host.
     */
    public String getHostName() {
        return hostName;
    }

    /**
     * Define o nome do host.
     * @param hostName nome do host.
     */
    public void setHostName(String hostName) {
        this.hostName = hostName;
    }

    /**
     * @return nome do usuario.
     */
    public String getUserName() {
        return userName;
    }

    /**
     * Define o nome do usuario.
     * @param userName o nome do usuario.
     */
    public void setUserName(String userName) {
        this.userName = userName;
    }

    /**
     * @return nome do projeto.
     */
    public String getProjectName() {
        return projectName;
    }

    /**
     * Define o nome do projeto.
     * @param projectName o nome do projeto.
     */
    public void setProjectName(String projectName) {
        this.projectName = projectName;
    }

    /**
     * @return versao do projeto.
     */
    public String getVersion() {
        return version;
    }

    /**
     * Define a versao.
     * @param version a versao.
     */
    public void setVersion(String version) {
        this.version = version;
    }

    /**
     * @return o id do grupo do artefato do projeto.
     */
    public String getGroupId() {
        return groupId;
    }

    /**
     * Define o id do grupo do artefato do projeto.
     * @param groupId id do grupo.
     */
    public void setGroupId(String groupId) {
        this.groupId = groupId;
    }

    /**
     * @return o id do artefato do projeto.
     */
    public String getArtifactId() {
        return artifactId;
    }

    /**
     * Define o id do artefato do projeto.
     * @param artifactId id do artefato do projeto.
     */
    public void setArtifactId(String artifactId) {
        this.artifactId = artifactId;
    }

    /**
     * @return o encoding usado para fazer build do projeto.
     */
    public String getEncoding() {
        return encoding;
    }

    /**
     * Define o encoding usado na maquina para fazer build do projeto.
     * @param encoding o encoding.
     */
    public void setEncoding(String encoding) {
        this.encoding = encoding;
    }

    /**
     * @return a jvm usada para fazer build do projeto.
     */
    public String getJvm() {
        return jvm;
    }

    /**
     * A Jvm usada para fazer build do projeto.
     * @param jvm a jvm.
     */
    public void setJvm(String jvm) {
        this.jvm = jvm;
    }

    /**
     * @return representacao em texto.
     */
    @Override
    public String toString() {
        StringBuilder sb = new StringBuilder();
        sb.append("Action: ").append(action).append(" ");
        sb.append("HostName: ").append(hostName).append(" ");
        sb.append("UserName: ").append(userName).append(" ");
        sb.append("Project: ").append(projectName).append(" ");
        sb.append("Version: ").append(version).append(" ");
        sb.append("GroupId: ").append(groupId).append(" ");
        sb.append("ArtifactId: ").append(artifactId).append(" ");
        sb.append("Encoding: ").append(encoding).append(" ");
        sb.append("JVM: ").append(jvm);
        return sb.toString();
    }
}
