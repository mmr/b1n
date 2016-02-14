/* Copyright (c) 2007, B1N.ORG
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *     * Redistributions of source code must retain the above copyright
 *       notice, this list of conditions and the following disclaimer.
 *     * Redistributions in binary form must reproduce the above copyright
 *       notice, this list of conditions and the following disclaimer in the
 *       documentation and/or other materials provided with the distribution.
 *     * Neither the name of the B1N.ORG organization nor the
 *       names of its contributors may be used to endorse or promote products
 *       derived from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS "AS IS" AND ANY
 * EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED. IN NO EVENT SHALL B1N.ORG OR ITS CONTRIBUTORS BE LIABLE FOR ANY
 * DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
 * ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 */
package org.b1n.ib.editor;

import java.util.regex.Matcher;
import java.util.regex.Pattern;

import org.b1n.ib.InsertBeautifierPlugin;
import org.b1n.ib.preferences.PreferencesConstants;

/**
 * Estrategia de formatacao para INSERTs.
 * @author Marcio Ribeiro
 * @date 07/10/2007
 */
public class InsertFormattingStrategy extends DefaultFormattingStrategy {
    /**
     * Devolve conteudo formatado de acordo com a estrategia abaixo.
     * 
     * <pre>
     * INSERT INTO tabela (colunaA, colunaB, colunaC, colunaD) VALUES (42, -3.141592, 'Melissa', null);
     * </pre>
     *
     * Vira:
     *
     * <pre>
     * INSERT INTO tabela ( colunaA, colunaB  , colunaC  , colunaD) VALUES
     *                    ( 42     , -3.141592, 'Melissa', null   );
     * </pre>
     * 
     * @param orig conteudo a ser formatado.
     * @param isLineStart true se for comeco de linha, false se nao.
     * @param indentation endentacao.
     * @param positions posicoes.
     * @return conteudo formatado.
     */
    @Override
    public String format(final String orig, final boolean isLineStart, final String indentation, final int[] positions) {
        String content = orig.replaceAll("[\r\n]", "").replaceAll("\\s{2,}", " ");
        Pattern p = Pattern.compile("^\\s*INSERT\\s+INTO\\s+([^\\s(]*)\\s*\\((.*)\\)\\s*VALUES\\s*\\((.*)\\)\\s*;", Pattern.CASE_INSENSITIVE);
        Matcher m = p.matcher(content);
        if (!m.find()) {
            return orig;
        }

        final String table = m.group(1);
        final String fs = m.group(2);
        final String vs = m.group(3);

        String[] fields = fs.split(",");
        if (fields.length < this.getMinFields()) {
            return content;
        }

        String[] values = new String[fields.length];
        Pattern pv = Pattern.compile("(?: *('[^\\']*') *,?)|(?:([^,]+),?)|(,)");
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
        q2.append(this.getSpaces(q1.length() - 1)).append("(");

        for (int i = 0; i < values.length; i++) {
            if ((fields[i] == null) || (values[i] == null)) {
                return orig;
            }
            String f = fields[i].trim();
            String v = values[i].trim();

            int lenF = f.length() - 1;
            int lenV = v.length() - 1;
            if (lenF >= lenV) {
                q1.append(" ").append(f).append(",");
                q2.append(" ").append(v).append(this.getSpaces(lenF - lenV)).append(",");
            } else {
                q1.append(" ").append(f).append(this.getSpaces(lenV - lenF)).append(",");
                q2.append(" ").append(v).append(",");
            }
        }
        return q1.toString().replaceAll(",$", "") + ") VALUES\n" + q2.toString().replaceAll(",$", "") + ");";
    }

    /**
     * Devolve string com a quantidade de espacos passada.
     * @param spacesToAdd espacos a serem adicionados.
     * @return string com a quantidade de espacos passada.
     */
    private String getSpaces(final int spacesToAdd) {
        StringBuilder sb = new StringBuilder(spacesToAdd);
        for (int i = 0; i < spacesToAdd; i++) {
            sb.append(" ");
        }
        return sb.toString();
    }

    /**
     * Devolve a quantidade minima de campos configurada a ser considerada para acionar (ou nao) o formatador.
     * @return a quantidade minima de campos configurada a ser considerada para acionar (ou nao) o formatador.
     */
    private Integer getMinFields() {
        return InsertBeautifierPlugin.getDefault().getPreferenceStore().getInt(PreferencesConstants.MIN_FIELDS);
    }
}