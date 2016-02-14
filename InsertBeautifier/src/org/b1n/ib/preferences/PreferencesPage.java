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
import org.eclipse.jface.preference.FieldEditorPreferencePage;
import org.eclipse.jface.preference.StringFieldEditor;
import org.eclipse.jface.resource.JFaceResources;
import org.eclipse.swt.widgets.Composite;
import org.eclipse.swt.widgets.Text;
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
        int items = PreferencesPage.MIN_FIELDS_MAX - PreferencesPage.MIN_FIELDS_MIN + 1;

        DATA = new String[items][2];
        for (int i = 0, j = PreferencesPage.MIN_FIELDS_MIN; i < items; i++, j++) {
            PreferencesPage.DATA[i][0] = String.valueOf(j);
            PreferencesPage.DATA[i][1] = String.valueOf(j);
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

        // StringFieldEditor minFields = new StringFieldEditor(PreferencesConstants.MIN_FIELDS, "&Mínimo de Campos:", 2, this.getFieldEditorParent());
        MyIntegerFieldEditor minFields = new MyIntegerFieldEditor(PreferencesConstants.MIN_FIELDS, "&Mínimo de Campos:", this.getFieldEditorParent(), 2);
        minFields.setValidRange(PreferencesPage.MIN_FIELDS_MIN, PreferencesPage.MIN_FIELDS_MAX);
        this.addField(minFields);

        this.addField(new ColorFieldEditor(PreferencesConstants.COLOR_INSERT, "&Insert:", this.getFieldEditorParent()));
        this.addField(new ColorFieldEditor(PreferencesConstants.COLOR_COMMENT, "&Comentário:", this.getFieldEditorParent()));
        this.addField(new ColorFieldEditor(PreferencesConstants.COLOR_STRING, "&String:", this.getFieldEditorParent()));
    }

    /**
     * Inicia pagina.
     * @param workbench workbench.
     */
    public void init(final IWorkbench workbench) {
        // do nothing
    }
    
    /**
     * Cópia da classe IntegerMyFieldEditor com a única diferença de chamar o super de StringFieldEditor passando o textLimit como width, fazendo o campo ficar do tamanho certo.
     * TODO (mmr) : entender por que diabos a classe IntegerMyFieldEditor nao coloca o campo com width correto.
     * @author Marcio Ribeiro
     * @date 08/10/2007
     */
    class MyIntegerFieldEditor extends StringFieldEditor {

        /** Mínimo valor válido. */
        private int minValidValue = 0;

        /** Máximo valor válido. */
        private int maxValidValue = Integer.MAX_VALUE;

        /** Limite padrao. */
        private static final int DEFAULT_TEXT_LIMIT = 10;

        /**
         * Creates an integer field editor.
         * @param name the name of the preference this field editor works on
         * @param labelText the label text of the field editor
         * @param parent the parent of the field editor's control
         */
        public MyIntegerFieldEditor(final String name, final String labelText, final Composite parent) {
            this(name, labelText, parent, MyIntegerFieldEditor.DEFAULT_TEXT_LIMIT);
        }

        /**
         * Creates an integer field editor.
         * @param name the name of the preference this field editor works on
         * @param labelText the label text of the field editor
         * @param parent the parent of the field editor's control
         * @param textLimit the maximum number of characters in the text.
         */
        public MyIntegerFieldEditor(final String name, final String labelText, final Composite parent, final int textLimit) {
            super(name, labelText, textLimit, parent);
            this.init(name, labelText);
            this.setTextLimit(textLimit);
            this.setEmptyStringAllowed(false);
            this.setErrorMessage(JFaceResources.getString("IntegerFieldEditor.errorMessage")); //$NON-NLS-1$
            this.createControl(parent);
        }

        /**
         * Sets the range of valid values for this field.
         * @param min the minimum allowed value (inclusive)
         * @param max the maximum allowed value (inclusive)
         */
        public void setValidRange(final int min, final int max) {
            this.minValidValue = min;
            this.maxValidValue = max;
            this.setErrorMessage(JFaceResources.format("IntegerFieldEditor.errorMessageRange", //$NON-NLS-1$
                    new Object[] { new Integer(min), new Integer(max) }));
        }

        /**
         * Verifica estado, devolve true se estiver ok, false se nao.
         * @return true se estiver ok, false se nao.
         */
        @Override
        protected boolean checkState() {

            Text text = this.getTextControl();

            if (text == null) {
                return false;
            }

            String numberString = text.getText();
            try {
                int number = Integer.valueOf(numberString).intValue();
                if ((number >= this.minValidValue) && (number <= this.maxValidValue)) {
                    this.clearErrorMessage();
                    return true;
                }

                this.showErrorMessage();
                return false;

            } catch (NumberFormatException e1) {
                this.showErrorMessage();
            }

            return false;
        }

        /**
         * Carrega.
         */
        @Override
        protected void doLoad() {
            Text text = this.getTextControl();
            if (text != null) {
                int value = this.getPreferenceStore().getInt(this.getPreferenceName());
                text.setText("" + value); //$NON-NLS-1$
            }

        }

        /**
         * Carrega o padrao.
         */
        @Override
        protected void doLoadDefault() {
            Text text = this.getTextControl();
            if (text != null) {
                int value = this.getPreferenceStore().getDefaultInt(this.getPreferenceName());
                text.setText("" + value); //$NON-NLS-1$
            }
            this.valueChanged();
        }

        /**
         * Salva.
         */
        @Override
        protected void doStore() {
            Text text = this.getTextControl();
            if (text != null) {
                Integer i = new Integer(text.getText());
                this.getPreferenceStore().setValue(this.getPreferenceName(), i.intValue());
            }
        }

        /**
         * Returns this field editor's current value as an integer.
         * @return the value
         */
        public int getIntValue() {
            return new Integer(this.getStringValue()).intValue();
        }
    }
}