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
package org.b1n.ib.preferences;

import org.b1n.ib.InsertBeautifierPlugin;
import org.eclipse.jface.preference.ColorFieldEditor;
import org.eclipse.jface.preference.ComboFieldEditor;
import org.eclipse.jface.preference.FieldEditorPreferencePage;
import org.eclipse.ui.IWorkbench;
import org.eclipse.ui.IWorkbenchPreferencePage;

/**
 * Pagina de configuracao do plugin.
 * @author Marcio Ribeiro
 * @date 07/10/2007
 */
public final class PreferencesPage extends FieldEditorPreferencePage implements IWorkbenchPreferencePage {
    /** Dados de combo para minimo de campos. */
    private static final String[][] DATA;

    static {
        DATA = new String[][] { { "1", "1" }, { "2", "2" }, { "3", "3" }, { "4", "4" }, { "5", "5" }, { "6", "6" }, { "7", "7" }, { "8", "8" }, { "9", "9" }, { "10", "10" } };
    }

    /**
     * Construtor.
     */
    public PreferencesPage() {
        super(FieldEditorPreferencePage.GRID);
        this.setPreferenceStore(InsertBeautifierPlugin.getDefault().getPreferenceStore());
        this.setDescription("InsertBeautifier Plugin Configuration");
    }

    /**
     * Cria campos.
     */
    @Override
    public void createFieldEditors() {
        this.addField(new ComboFieldEditor(PreferencesConstants.MIN_FIELDS, "&Mínimo de Campos: ", PreferencesPage.DATA, this.getFieldEditorParent()));

        this.addField(new ColorFieldEditor(PreferencesConstants.COLOR_INSERT, "&Insert", this.getFieldEditorParent()));
        this.addField(new ColorFieldEditor(PreferencesConstants.COLOR_COMMENT, "&Comentário", this.getFieldEditorParent()));
        this.addField(new ColorFieldEditor(PreferencesConstants.COLOR_STRING, "&String", this.getFieldEditorParent()));
    }

    /**
     * Inicia pagina.
     * @param workbench workbench.
     */
    @Override
    public void init(final IWorkbench workbench) {
        // do nothing
    }
}