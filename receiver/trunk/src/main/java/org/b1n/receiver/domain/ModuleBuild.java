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

    /**
     * @return o projeto.
     */
    public Project getProject() {
        return project;
    }

    /**
     * Define o projeto.
     * @param project o projeto.
     */
    public void setProject(Project project) {
        this.project = project;
    }

    /**
     * @return o build do pai dese modulo.
     */
    public ProjectBuild getProjectBuild() {
        return projectBuild;
    }

    /**
     * Define o build do pai.
     * @param projectBuild build do pai.
     */
    public void setProjectBuild(ProjectBuild projectBuild) {
        this.projectBuild = projectBuild;
    }
}
