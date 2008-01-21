package org.b1n.receiver.domain;

import java.util.List;

import javax.persistence.CascadeType;
import javax.persistence.Column;
import javax.persistence.Entity;
import javax.persistence.OneToMany;

/**
 * Build.
 * @author Marcio Ribeiro
 * @date Jan 20, 2008
 */
@Entity
public class Build extends Project {

    @Column(nullable = false)
    private String hostName;

    @Column(nullable = false)
    private String hostIp;

    @Column(nullable = false)
    private String hostRequestIp;

    @Column(nullable = false)
    private String projectName;

    @OneToMany(cascade = CascadeType.ALL)
    private List<Module> modules;

    private String userName;

    private String encoding;

    private String jvm;

    public String getHostIp() {
        return hostIp;
    }

    public void setHostIp(String hostIp) {
        this.hostIp = hostIp;
    }

    public String getHostRequestIp() {
        return hostRequestIp;
    }

    public void setHostRequestIp(String hostRequestIp) {
        this.hostRequestIp = hostRequestIp;
    }

    public List<Module> getModules() {
        return modules;
    }

    public void addModule(Module module) {
        this.modules.add(module);
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
     * @return <code>true</code> se possui modulos, <code>false</code> se nao.
     */
    public boolean hasModules() {
        return modules != null && !modules.isEmpty();
    }
    
    /**
     * @return representacao em texto.
     */
    @Override
    public String toString() {
        StringBuilder sb = new StringBuilder();
        sb.append("HostName: ").append(hostName).append("\n");
        sb.append("UserName: ").append(userName).append("\n");
        sb.append("Project: ").append(projectName).append("\n");
        sb.append("Encoding: ").append(encoding).append("\n");
        sb.append("JVM: ").append(jvm).append("\n");
        sb.append(super.toString());
        return sb.toString();
    }
}
