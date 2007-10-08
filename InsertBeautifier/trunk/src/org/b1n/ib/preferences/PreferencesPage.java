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