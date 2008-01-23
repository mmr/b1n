package org.b1n.receiver.domain;

import java.text.DateFormat;
import java.text.NumberFormat;
import java.text.SimpleDateFormat;
import java.util.Date;

import javax.persistence.Column;
import javax.persistence.MappedSuperclass;

import org.b1n.framework.persistence.SimpleEntity;

/**
 * Build.
 * @author Marcio Ribeiro
 * @date Jan 20, 2008
 */
@MappedSuperclass
public abstract class Build extends SimpleEntity {
    private static final DateFormat DATE_FORMATTER = new SimpleDateFormat("dd/MM/yyyy hh:mm:ss");
    
    @Column(nullable = false)
    private Date startTime;

    private Date endTime;

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

    public long getBuildTime() {
        if (endTime == null) {
            return 0;
        }
        return endTime.getTime() - startTime.getTime();
    }

    public String getFormattedBuildTime() {
        NumberFormat nf = NumberFormat.getInstance();
        nf.setMinimumIntegerDigits(2);
        int sec = (int) (getBuildTime() / 1000);

        StringBuilder sb = new StringBuilder();
        int mins = sec / 60;
        if (mins > 0) {
            sb.append(nf.format(mins)).append("\"");
        }

        sb.append(nf.format(sec % 60)).append("'");
        return sb.toString();
    }

    public String getFormattedStartTime() {
        return showDate(startTime);
    }

    public String getFormattedEndTime() {
        return showDate(endTime);
    }

    private String showDate(Date date) {
        if (date == null) {
            return null;
        } else {
            return DATE_FORMATTER.format(date);
        }
    }
}
