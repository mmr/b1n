package org.b1n.receiver.domain;

import javax.persistence.Entity;
import javax.persistence.ManyToOne;

/**
 * Dados enviados pelo Informer.
 * @author Marcio Ribeiro
 * @date Jan 20, 2008
 */
@Entity
public class Module extends Project {
    @ManyToOne
    private Build build;

    public Build getBuild() {
        return build;
    }

    public void setBuild(Build build) {
        this.build = build;
    }

    @Override
    public String toString() {
        StringBuilder sb = new StringBuilder();
        sb.append("Build: ").append(build.getProjectName());
        return sb.toString();
    }
}
