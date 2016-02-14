package org.b1n.jirator.jira;

import java.util.HashMap;
import java.util.Map;

/**
 * @author Marcio Ribeiro
 * @date May 3, 2008
 */
public class Row {
    private final Map<String, Object> columns = new HashMap<String, Object>();

    /**
     * Coloca valor em coluna para essa linha.
     * @param colName coluna.
     * @param value valor.
     */
    void put(final String colName, final Object value) {
        columns.put(colName, value);
    }

    /**
     * Devolve objeto com o nome de coluna passado.
     * @param colName coluna.
     * @return objeto.
     */
    public Object get(final String colName) {
        return columns.get(colName);
    }

    /**
     * @return to string.
     */
    @Override
    public String toString() {
        return columns.toString();
    }
}