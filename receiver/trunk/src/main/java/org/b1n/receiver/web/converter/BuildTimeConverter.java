package org.b1n.receiver.web.converter;

import java.text.NumberFormat;

import javax.faces.component.UIComponent;
import javax.faces.context.FacesContext;
import javax.faces.convert.Converter;
import javax.faces.convert.ConverterException;

/**
 * @author Marcio Ribeiro
 * @date Jan 28, 2008
 */
public class BuildTimeConverter implements Converter {
    private static final NumberFormat NF;

    static {
        NF = NumberFormat.getInstance();
        NF.setMinimumIntegerDigits(2);
    }

    public Object getAsObject(FacesContext context, UIComponent component, String value) throws ConverterException {
        throw new UnsupportedOperationException();
    }

    public String getAsString(FacesContext context, UIComponent component, Object value) throws ConverterException {
        final int secsInMin = 60;
        final int secsInMili = 1000;

        long buildTime = (Long) value;

        int sec = (int) (buildTime / secsInMili);

        StringBuilder sb = new StringBuilder();
        int mins = sec / secsInMin;
        if (mins > 0) {
            sb.append(NF.format(mins)).append("\"");
        }

        sb.append(NF.format(sec % secsInMin)).append("'");

        return sb.toString();
    }
}
