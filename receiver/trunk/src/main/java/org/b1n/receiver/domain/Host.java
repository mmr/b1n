package org.b1n.receiver.domain;

import javax.persistence.Column;
import javax.persistence.Entity;
import javax.persistence.Table;
import javax.persistence.UniqueConstraint;

import org.b1n.framework.persistence.RecordEntity;

/**
 * @author Marcio Ribeiro
 * @date Jan 23, 2008
 */
@Entity
@Table(uniqueConstraints = { @UniqueConstraint(columnNames = { "hostName", "hostIp" }) })
public class Host extends RecordEntity {
    @Column(nullable = false)
    private String hostName;

    @Column(nullable = false)
    private String hostIp;

    @Column(nullable = false)
    private String os;

    private String jvm;

    private String encoding;

    public Host(String hostName, String hostIp, String os, String jvm, String encoding) {
        this.hostName = hostName;
        this.hostIp = hostIp;
        this.os = os;
        this.jvm = jvm;
        this.encoding = encoding;
    }

    public String getHostIp() {
        return hostIp;
    }

    public void setHostIp(String hostIp) {
        this.hostIp = hostIp;
    }

    public String getHostName() {
        return hostName;
    }

    public void setHostName(String hostName) {
        this.hostName = hostName;
    }

    public String getOs() {
        return os;
    }

    public void setOs(String os) {
        this.os = os;
    }

    public String getJvm() {
        return jvm;
    }

    public void setJvm(String jvm) {
        this.jvm = jvm;
    }

    public String getEncoding() {
        return encoding;
    }

    public void setEncoding(String encoding) {
        this.encoding = encoding;
    }

}
