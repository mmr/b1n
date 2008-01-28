package org.b1n.receiver.web.converter;

import javax.faces.component.UIComponent;
import javax.faces.context.FacesContext;
import javax.faces.convert.Converter;
import javax.faces.convert.ConverterException;

/**
 * @author Marcio Ribeiro
 * @date Jan 28, 2008
 */
public class BooleanConverter implements Converter {
    private static final String STRING_TRUE = "Sim";
    private static final String STRING_FALSE = "Não";

    public Object getAsObject(FacesContext context, UIComponent component, String value) throws ConverterException {
        throw new UnsupportedOperationException();
    }

    public String getAsString(FacesContext context, UIComponent component, Object value) throws ConverterException {
        return Boolean.TRUE.equals(value) ? STRING_TRUE : STRING_FALSE;
    }
}
