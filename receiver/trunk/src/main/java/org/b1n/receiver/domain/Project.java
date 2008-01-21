package org.b1n.receiver.domain;

import java.util.Date;

import javax.persistence.Column;
import javax.persistence.MappedSuperclass;

import org.b1n.framework.persistence.SimpleEntity;

/**
 * Projeto.
 * @author Marcio Ribeiro
 * @date Jan 21, 2008
 */
@MappedSuperclass
public abstract class Project extends SimpleEntity {
    @Column(nullable = false)
    private String groupId;

    @Column(nullable = false)
    private String artifactId;

    @Column(nullable = false)
    private String version;

    @Column(nullable = false)
    private Date startTime;

    private Date endTime;

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

    public Date getStartTime() {
        return startTime;
    }

    public void setStartTime(Date startTime) {
        this.startTime = startTime;
    }

    public Date getEndTime() {
        return endTime;
    }

    public void setEndTime(Date endTime) {
        this.endTime = endTime;
    }

    public long getTimeDelta() {
        if (endTime == null) {
            return 0;
        }
        return endTime.getTime() - startTime.getTime();
    }

    @Override
    public String toString() {
        StringBuilder sb = new StringBuilder();
        sb.append("GroupId: ").append(groupId).append("\n");
        sb.append("ArtifactId: ").append(artifactId).append("\n");
        sb.append("Version: ").append(version).append("\n");
        sb.append("StartTime: ").append(startTime).append("\n");
        sb.append("EndTime: ").append(endTime);
        return sb.toString();
    }
}
