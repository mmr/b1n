package org.b1n.receiver.domain;

import java.text.DateFormat;
import java.text.NumberFormat;
import java.text.SimpleDateFormat;
import java.util.Date;

import javax.persistence.Column;
import javax.persistence.MappedSuperclass;

import org.b1n.framework.persistence.JpaEntity;

/**
 * Build.
 * @author Marcio Ribeiro
 * @date Jan 20, 2008
 */
@MappedSuperclass
public abstract class Build extends JpaEntity {
    /** Formatter. */
    private static final DateFormat DATE_FORMATTER = new SimpleDateFormat("dd/MM/yyyy hh:mm:ss");

    @Column(nullable = false)
    private Date startTime;

    private Date endTime;

    private boolean skipTests;

    /**
     * @return <code>true</code> se build pulou os testes, <code>false</code> se nao.
     */
    public boolean isSkipTests() {
        return skipTests;
    }

    /**
     * Define se pulou testes.
     * @param skipTests <code>true</code> se pulou testes, <code>false</code> caso contrario.
     */
    public void setSkipTests(boolean skipTests) {
        this.skipTests = skipTests;
    }

    /**
     * @return hora de inicio de build.
     */
    public Date getStartTime() {
        return startTime;
    }

    /**
     * Define hora de inicio de build.
     * @param startTime inicio de build.
     */
    public void setStartTime(Date startTime) {
        this.startTime = startTime;
    }

    /**
     * @return fim de build.
     */
    public Date getEndTime() {
        return endTime;
    }

    /**
     * Define hora de fim de build.
     * @param endTime hora de fim de build.
     */
    public void setEndTime(Date endTime) {
        this.endTime = endTime;
    }

    /**
     * @return delta entre fim e inicio de build em milisegundos.
     */
    public long getBuildTime() {
        if (endTime == null) {
            return 0;
        }
        return endTime.getTime() - startTime.getTime();
    }

    /**
     * @return tempo de build (fim - inicio) formatado.
     */
    public String getFormattedBuildTime() {
        final int secsInMin = 60;
        final int secsInMili = 1000;

        NumberFormat nf = NumberFormat.getInstance();
        nf.setMinimumIntegerDigits(2);
        int sec = (int) (getBuildTime() / secsInMili);

        StringBuilder sb = new StringBuilder();
        int mins = sec / secsInMin;
        if (mins > 0) {
            sb.append(nf.format(mins)).append("\"");
        }

        sb.append(nf.format(sec % secsInMin)).append("'");
        return sb.toString();
    }

    /**
     * @return hora de inicio formatada.
     */
    public String getFormattedStartTime() {
        return showDate(startTime);
    }

    /**
     * @return hora de fim formatada.
     */
    public String getFormattedEndTime() {
        return showDate(endTime);
    }

    /**
     * Metodo auxiliar para formatar data/hora.
     * @param date data/hora.
     * @return data/hora formatada.
     */
    private String showDate(Date date) {
        if (date == null) {
            return null;
        } else {
            return DATE_FORMATTER.format(date);
        }
    }
}
