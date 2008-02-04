package org.b1n.receiver.web.converter;


/**
 * @author Marcio Ribeiro
 * @date Jan 28, 2008
 */
public class BuildTimeConverter {//implements Converter {
//    private static final NumberFormat NF;
//
//    static {
//        NF = NumberFormat.getInstance();
//        NF.setMinimumIntegerDigits(2);
//    }
//
//    /**
//     * Devolve objeto a partir de string. Nao implementado.
//     * @param context contexto.
//     * @param component componente.
//     * @param value valor.
//     * @return o objeto.
//     */
//    public Object getAsObject(FacesContext context, UIComponent component, String value) {
//        throw new UnsupportedOperationException();
//    }
//
//    /**
//     * Converte objeto para string.
//     * @param context o contexto jsf.
//     * @param component o componente.
//     * @param value o objeto a ser convertido.
//     * @return string a partir de objeto.
//     */
//    public String getAsString(FacesContext context, UIComponent component, Object value) {
//        final int secsInMin = 60;
//        final int secsInMili = 1000;
//
//        long buildTime = (Long) value;
//
//        int sec = (int) (buildTime / secsInMili);
//
//        StringBuilder sb = new StringBuilder();
//        int mins = sec / secsInMin;
//        if (mins > 0) {
//            sb.append(NF.format(mins)).append("\"");
//        }
//
//        sb.append(NF.format(sec % secsInMin)).append("'");
//
//        return sb.toString();
//    }
}
