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
package org.b1n.ib;

import org.b1n.ib.preferences.PreferencesConstants;
import org.eclipse.jface.preference.IPreferenceStore;
import org.eclipse.jface.preference.PreferenceConverter;
import org.eclipse.swt.graphics.RGB;
import org.eclipse.ui.plugin.AbstractUIPlugin;
import org.osgi.framework.BundleContext;

/**
 * Cuida do ciclo de vida do plugin.
 * @author Marcio Ribeiro
 * @date 07/10/2007
 */
public class InsertBeautifierPlugin extends AbstractUIPlugin {
    /** ID de plgin. */
    public static final String PLUGIN_ID = "InsertBeautifier";

    /** Metade de um byte, 128. */
    private static final int HALF = 128;

    /** Cor padrão para INSERT. */
    private static RGB defaultColorInsert;

    /** Cor padrão para comentário. */
    private static RGB defaultColorComment;

    /** Cor padrão para string. */
    private static RGB defaultColorString;

    /** Instancia compartilhada de plugin. */
    private static InsertBeautifierPlugin plugin;

    /**
     * Metodo chamado no inicio da execucao do plugin.
     * @param context contexto.
     * @throws Exception caso algo de inesperado ocorra.
     */
    @Override
    public void start(final BundleContext context) throws Exception {
        super.start(context);
        InsertBeautifierPlugin.plugin = this;
        InsertBeautifierPlugin.defaultColorInsert = new RGB(0, 0, HALF);
        InsertBeautifierPlugin.defaultColorComment = new RGB(0, HALF, 0);
        InsertBeautifierPlugin.defaultColorString = new RGB(HALF, HALF, HALF);
    }

    /**
     * Metodo chamado ao parar o plugin.
     * @param context contexto.
     * @throws Exception caso algo de inesperado ocorra.
     */
    @Override
    public void stop(final BundleContext context) throws Exception {
        InsertBeautifierPlugin.plugin = null;
        InsertBeautifierPlugin.defaultColorInsert = null;
        InsertBeautifierPlugin.defaultColorComment = null;
        InsertBeautifierPlugin.defaultColorString = null;
        super.stop(context);
    }

    /**
     * Devolve instancia compartilhada de plugin.
     * @return instancia compartilhada de plugin.
     */
    public static InsertBeautifierPlugin getDefault() {
        return InsertBeautifierPlugin.plugin;
    }

    /**
     * Configura valores padroes para pagina de configuracao de plugin.
     * @param store guardador de configuracao.
     */
    @Override
    protected void initializeDefaultPreferences(final IPreferenceStore store) {
        store.setDefault(PreferencesConstants.MIN_FIELDS, "4");

        PreferenceConverter.setDefault(this.getPreferenceStore(), PreferencesConstants.COLOR_INSERT, InsertBeautifierPlugin.defaultColorInsert);
        PreferenceConverter.setDefault(this.getPreferenceStore(), PreferencesConstants.COLOR_COMMENT, InsertBeautifierPlugin.defaultColorComment);
        PreferenceConverter.setDefault(this.getPreferenceStore(), PreferencesConstants.COLOR_STRING, InsertBeautifierPlugin.defaultColorString);
    }
}