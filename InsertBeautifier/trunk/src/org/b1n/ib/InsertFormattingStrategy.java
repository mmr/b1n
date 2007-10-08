package org.b1n.ib;

import java.util.regex.Matcher;
import java.util.regex.Pattern;

/**
 * @author Marcio Ribeiro
 * @date 07/10/2007
 */
public class InsertFormattingStrategy extends DefaultFormattingStrategy {
    @Override
    public String format(final String orig, boolean isLineStart, String indentation, int[] positions) {
        String content = orig.replaceAll("[\r\n]", "");
        Pattern p = Pattern.compile("^\\s*INSERT\\s+INTO\\s+([^\\s(]*)\\s*\\(([^)]+)\\)\\s*VALUES\\s*\\(([^)]+)\\)\\s*;", Pattern.CASE_INSENSITIVE);
        Matcher m = p.matcher(content);
        if (!m.find()) {
            return orig;
        }

        String table = m.group(1);
        String fs = m.group(2);
        String vs = m.group(3);

        String[] fields = fs.split(",");
        String[] values = new String[fields.length];
        Pattern pv = Pattern.compile("(?: *('[^\']*') *,?)|(?:([^,]+),?)|(,)");
        Matcher mv = pv.matcher(vs);
        int j = 0;
        while (mv.find()) {
            values[j] = mv.group(0).replaceAll(",$", "");
            j++;
        }

        if (fields.length != values.length) {
            return orig;
        }

        StringBuilder q1 = new StringBuilder();
        q1.append("INSERT INTO " + table + " (");

        StringBuilder q2 = new StringBuilder();
        q2.append(getSpaces(q1.length() - 1)).append("(");

        for (int i = 0; i < values.length; i++) {
            if (fields[i] == null || values[i] == null) {
                return orig;
            }
            String f = fields[i].trim();
            String v = values[i].trim();

            int lenF = f.length() - 1;
            int lenV = v.length() - 1;
            if (lenF >= lenV) {
                q1.append(" ").append(f).append(",");
                q2.append(" ").append(v).append(getSpaces(lenF - lenV)).append(",");
            } else {
                q1.append(" ").append(f).append(getSpaces(lenV - lenF)).append(",");
                q2.append(" ").append(v).append(",");
            }
        }
        return q1.toString().replaceAll(",$", "") + ") VALUES\n" + q2.toString().replaceAll(",$", "") + ");";
    }

    private String getSpaces(int spacesToAdd) {
        StringBuilder sb = new StringBuilder(spacesToAdd);
        for (int i = 0; i < spacesToAdd; i++) {
            sb.append(" ");
        }
        return sb.toString();
    }
}