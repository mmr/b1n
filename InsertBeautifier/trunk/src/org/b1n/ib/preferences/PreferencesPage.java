/*
 * Copyright (c) 2007, B1N.ORG All rights reserved. Redistribution and use in source and binary forms, with or without modification, are permitted provided that the following conditions are met: * Redistributions of source code must retain the above copyright notice, this list of conditions and the following disclaimer. * Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the following disclaimer in the documentation and/or other materials
 * provided with the distribution. * Neither the name of the B1N.ORG organization nor the names of its contributors may be used to endorse or promote products derived from this software without specific prior written permission. THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL B1N.ORG OR ITS CONTRIBUTORS
 * BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 */
package org.b1n.ib.preferences;

import org.b1n.ib.InsertBeautifierPlugin;
import org.eclipse.jface.preference.ColorFieldEditor;
import org.eclipse.jface.preference.FieldEditorPreferencePage;
import org.eclipse.jface.preference.IntegerFieldEditor;
import org.eclipse.ui.IWorkbench;
import org.eclipse.ui.IWorkbenchPreferencePage;

/**
 * Pagina de configuracao do plugin.
 * @author Marcio Ribeiro
 * @date 07/10/2007
 */
public final class PreferencesPage extends FieldEditorPreferencePage implements IWorkbenchPreferencePage {
    /** Mínimo para configuração de campos mínimos para formatação. */
    private static final int MIN_FIELDS_MIN = 1;

    /** Máximo para configuração de campos mínimos para formatação. */
    private static final int MIN_FIELDS_MAX = 10;

    /** Dados de combo para minimo de campos. */
    private static final String[][] DATA;

    static {
        // Popula array bidimensional com dados de combo
        int items = MIN_FIELDS_MAX - MIN_FIELDS_MIN + 1;

        DATA = new String[items][2];
        for (int i = 0, j = MIN_FIELDS_MIN; i < items; i++, j++) {
            DATA[i][0] = String.valueOf(j);
            DATA[i][1] = String.valueOf(j);
        }
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
        // XXX (mmr) : ComboFieldEditor soh funciona em 3.3, comentado por enquanto
        // this.addField(new ComboFieldEditor(PreferencesConstants.MIN_FIELDS, "&Mínimo de Campos: ", PreferencesPage.DATA, this.getFieldEditorParent()));

        IntegerFieldEditor minFields = new IntegerFieldEditor(PreferencesConstants.MIN_FIELDS, "&Mínimo de Campos:", this.getFieldEditorParent(), 2);
        minFields.setValidRange(MIN_FIELDS_MIN, MIN_FIELDS_MAX);
        this.addField(minFields);

        this.addField(new ColorFieldEditor(PreferencesConstants.COLOR_INSERT, "&Insert:", this.getFieldEditorParent()));
        this.addField(new ColorFieldEditor(PreferencesConstants.COLOR_COMMENT, "&Comentário:", this.getFieldEditorParent()));
        this.addField(new ColorFieldEditor(PreferencesConstants.COLOR_STRING, "&String:", this.getFieldEditorParent()));
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