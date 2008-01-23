package org.b1n.receiver.domain;

import javax.persistence.Column;
import javax.persistence.Entity;
import javax.persistence.Table;
import javax.persistence.UniqueConstraint;

import org.b1n.framework.persistence.RecordEntity;

/**
 * Projeto.
 * @author Marcio Ribeiro
 * @date Jan 21, 2008
 */
@Entity
@Table(uniqueConstraints = { @UniqueConstraint(columnNames = { "groupId", "artifactId", "version" }) })
public class Project extends RecordEntity {
    @Column(nullable = false)
    private String groupId;

    @Column(nullable = false)
    private String artifactId;

    @Column(nullable = false)
    private String version;

    private String projectName;

    public Project(String groupId, String artifactId, String version, String projectName) {
        this.groupId = groupId;
        this.artifactId = artifactId;
        this.version = version;
        this.projectName = projectName;
    }
    
    public String getProjectName() {
        return projectName;
    }

    public void setProjectName(String projectName) {
        this.projectName = projectName;
    }

    public String getGroupId() {
        return groupId;
    }

    public void setGroupId(String groupId) {
        this.groupId = groupId;
    }

    public String getArtifactId() {
        return artifactId;
    }

    public void setArtifactId(String artifactId) {
        this.artifactId = artifactId;
    }

    public String getVersion() {
        return version;
    }

    public void setVersion(String version) {
        this.version = version;
    }

    @Override
    public String toString() {
        StringBuilder sb = new StringBuilder();
        sb.append(groupId).append(" / ").append(artifactId).append(" ").append(version);
        return sb.toString();
    }
}
