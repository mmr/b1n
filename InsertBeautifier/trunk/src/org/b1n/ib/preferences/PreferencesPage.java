package org.b1n.ib.preferences;

import org.b1n.ib.InsertBeautifierPlugin;
import org.eclipse.jface.preference.ColorFieldEditor;
import org.eclipse.jface.preference.ComboFieldEditor;
import org.eclipse.jface.preference.FieldEditorPreferencePage;
import org.eclipse.ui.IWorkbench;
import org.eclipse.ui.IWorkbenchPreferencePage;

/**
 * @author Marcio Ribeiro
 * @date 07/10/2007
 */
public class PreferencesPage extends FieldEditorPreferencePage implements IWorkbenchPreferencePage {
    private static final String[][] data;

    static {
        data = new String[][] {
                { "1", "1" },
                { "2", "2" },
                { "3", "3" },
                { "4", "4" },
                { "5", "5" },
                { "6", "6" },
                { "7", "7" },
                { "8", "8" },
                { "9", "9" },
                { "10", "10" } };
    }

    public PreferencesPage() {
        super(GRID);
        setPreferenceStore(InsertBeautifierPlugin.getDefault().getPreferenceStore());
        setDescription("InsertBeautifier Plugin Configuration");
    }

    @Override
    public void createFieldEditors() {
        addField(new ComboFieldEditor(PreferencesConstants.MIN_FIELDS, "&Mínimo de Campos: ", data, getFieldEditorParent()));

        addField(new ColorFieldEditor(PreferencesConstants.COLOR_INSERT, "&Insert", getFieldEditorParent()));
        addField(new ColorFieldEditor(PreferencesConstants.COLOR_COMMENT, "&Comentário", getFieldEditorParent()));
        addField(new ColorFieldEditor(PreferencesConstants.COLOR_STRING, "&String", getFieldEditorParent()));
    }

    @Override
    public void init(IWorkbench workbench) {
        // do nothing
    }
}