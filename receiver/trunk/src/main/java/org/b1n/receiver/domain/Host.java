package org.b1n.receiver.domain;

import javax.persistence.Column;
import javax.persistence.Entity;
import javax.persistence.GeneratedValue;
import javax.persistence.GenerationType;
import javax.persistence.Id;
import javax.persistence.SequenceGenerator;
import javax.persistence.Table;
import javax.persistence.UniqueConstraint;

import org.b1n.framework.persistence.RecordEntity;

/**
 * Maquina onde o build esta sendo executado.
 * @author Marcio Ribeiro
 * @date Jan 23, 2008
 */
@Entity
@Table(uniqueConstraints = { @UniqueConstraint(columnNames = { "hostName", "hostIp" }) })
@SequenceGenerator(name = "seq_host", sequenceName = "seq_host")
public class Host extends RecordEntity {
    @Id
    @GeneratedValue(strategy = GenerationType.AUTO, generator = "seq_host")
    private Long id;

    @Column(nullable = false)
    private String hostName;

    @Column(nullable = false)
    private String hostIp;

    @Column(nullable = false)
    private String operatingSystem;

    private String jvm;

    private String encoding;

    /**
     * Construtor default para o hibernate.
     */
    public Host() {
        // do nothing
    }

    /**
     * @return id.
     */
    public Long getId() {
        return this.id;
    }

    /**
     * Define id.
     * @param id o id.
     */
    public void setId(Long id) {
        this.id = id;
    }

    /**
     * Construtor.
     * @param hostName nome do host.
     * @param hostIp ip do host.
     * @param os sistema operacional.
     * @param jvm java virtual machine.
     * @param encoding encoding da maquina.
     */
    public Host(String hostName, String hostIp, String os, String jvm, String encoding) {
        this.hostName = hostName;
        this.hostIp = hostIp;
        this.operatingSystem = os;
        this.jvm = jvm;
        this.encoding = encoding;
    }

    /**
     * @return endereco ip do host.
     */
    public String getHostIp() {
        return hostIp;
    }

    /**
     * Define endereco ip do host.
     * @param hostIp o endereco ip.
     */
    public void setHostIp(String hostIp) {
        this.hostIp = hostIp;
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
     * @return o sistema operacinoal.
     */
    public String getOperatingSystem() {
        return operatingSystem;
    }

    /**
     * Define o sistema operacional.
     * @param os o sistema operacional.
     */
    public void setOperatingSystem(String os) {
        this.operatingSystem = os;
    }

    /**
     * @return a java virtual machine usada.
     */
    public String getJvm() {
        return jvm;
    }

    /**
     * Define a jvm.
     * @param jvm a jvm.
     */
    public void setJvm(String jvm) {
        this.jvm = jvm;
    }

    /**
     * @return encoding usado no host do build.
     */
    public String getEncoding() {
        return encoding;
    }

    /**
     * Define o encoding.
     * @param encoding encoding usado no host do build.
     */
    public void setEncoding(String encoding) {
        this.encoding = encoding;
    }

    /**
     * @return representacao em texto.
     */
    @Override
    public String toString() {
        StringBuilder sb = new StringBuilder();
        sb.append(hostName).append(" (").append(hostIp).append(")");
        return sb.toString();
    }
}
