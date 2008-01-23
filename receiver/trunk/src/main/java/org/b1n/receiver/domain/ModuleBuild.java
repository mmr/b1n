package org.b1n.receiver.domain;

import javax.persistence.Entity;
import javax.persistence.JoinColumn;
import javax.persistence.ManyToOne;

/**
 * Dados enviados pelo Informer.
 * @author Marcio Ribeiro
 * @date Jan 20, 2008
 */
@Entity
public class ModuleBuild extends Build {
    @ManyToOne
    @JoinColumn(nullable = false)
    private ProjectBuild projectBuild;

    @ManyToOne
    @JoinColumn(nullable = false)
    private Project project;

    public Project getProject() {
        return project;
    }

    public void setProject(Project project) {
        this.project = project;
    }

    public ProjectBuild getProjectBuild() {
        return projectBuild;
    }

    public void setProjectBuild(ProjectBuild projectBuild) {
        this.projectBuild = projectBuild;
    }
}
