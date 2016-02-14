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

import java.util.HashMap;
import java.util.Map;

import org.b1n.ib.InsertBeautifierPlugin;
import org.b1n.ib.preferences.PreferencesConstants;
import org.eclipse.jface.preference.IPreferenceStore;
import org.eclipse.jface.preference.PreferenceConverter;
import org.eclipse.swt.graphics.Color;
import org.eclipse.swt.graphics.RGB;
import org.eclipse.swt.widgets.Display;

/**
 * Gerenciador de cores. Cuida de instanciar/liberar cores para tipos especificos do editor.
 * @author Marcio Ribeiro
 * @date 07/10/2007
 */
public class ColorManager {

    /** Tabela de cores. */
    private Map<RGB, Color> colorTable = new HashMap<RGB, Color>();

    /**
     * Libera memoria de cores.
     */
    public void dispose() {
        for (Color color : this.colorTable.values()) {
            color.dispose();
        }
    }

    /**
     * Devolve cor a partir de uma configuracao rgb.
     * @param rgb configuracao red-green-blue passada.
     * @return cor a partir de uma configuracao rgb.
     */
    private Color getColor(final RGB rgb) {
        Color color = this.colorTable.get(rgb);
        if (color == null) {
            color = new Color(Display.getCurrent(), rgb);
            this.colorTable.put(rgb, color);
        }
        return color;
    }

    /**
     * Devolve cor para a configuracao passada (recupera de preferenceStore).
     * @param confName nome de configuracao.
     * @return cor para a configuracao passada.
     */
    private Color getConfiguredColor(final String confName) {
        IPreferenceStore ps = InsertBeautifierPlugin.getDefault().getPreferenceStore();
        return this.getColor(PreferenceConverter.getColor(ps, confName));
    }

    /**
     * Devolve cor para insert.
     * @return cor para insert.
     */
    public Color getInsertColor() {
        return this.getConfiguredColor(PreferencesConstants.COLOR_INSERT);
    }

    /**
     * Devolve cor para comentario.
     * @return cor para comentario.
     */
    public Color getCommentColor() {
        return this.getConfiguredColor(PreferencesConstants.COLOR_COMMENT);
    }

    /**
     * Devolve cor para strings em inserts.
     * @return cor para strings em inserts.
     */
    public Color getStringColor() {
        return this.getConfiguredColor(PreferencesConstants.COLOR_STRING);
    }
}