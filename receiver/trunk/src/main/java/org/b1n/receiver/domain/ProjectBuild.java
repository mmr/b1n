package org.b1n.receiver.domain;

import java.util.List;

import javax.persistence.CascadeType;
import javax.persistence.Entity;
import javax.persistence.JoinColumn;
import javax.persistence.ManyToOne;
import javax.persistence.OneToMany;

/**
 * Build.
 * @author Marcio Ribeiro
 * @date Jan 20, 2008
 */
@Entity
public class ProjectBuild extends Build {
    @ManyToOne
    @JoinColumn(nullable = false)
    private Project project;

    @OneToMany(cascade = CascadeType.ALL, mappedBy = "projectBuild")
    private List<ModuleBuild> modules;

    @ManyToOne
    @JoinColumn(nullable = false)
    private User user;

    @ManyToOne
    private Host host;

    public Project getProject() {
        return project;
    }

    public void setProject(Project project) {
        this.project = project;
    }

    public List<ModuleBuild> getModules() {
        return modules;
    }

    public void addModule(ModuleBuild module) {
        this.modules.add(module);
    }

    public User getUser() {
        return user;
    }

    public void setUser(User user) {
        this.user = user;
    }

    public Host getHost() {
        return host;
    }

    public void setHost(Host host) {
        this.host = host;
    }
}
