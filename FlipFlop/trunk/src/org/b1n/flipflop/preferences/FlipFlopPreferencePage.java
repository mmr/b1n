package org.b1n.flipflop.preferences;

import org.b1n.flipflop.FlipFlopPlugin;
import org.eclipse.jface.preference.FieldEditorPreferencePage;
import org.eclipse.jface.preference.FileFieldEditor;
import org.eclipse.jface.preference.RadioGroupFieldEditor;
import org.eclipse.jface.preference.StringFieldEditor;
import org.eclipse.ui.IWorkbench;
import org.eclipse.ui.IWorkbenchPreferencePage;

/**
 * This class represents a preference page that is contributed to the Preferences dialog. By subclassing <samp>FieldEditorPreferencePage </samp>, we can use the
 * field support built into JFace that allows us to create a page that is small and knows how to save, restore and apply itself. <p>This page is used to modify
 * preferences only. They are stored in the preference store that belongs to the main plug-in class. That way, preferences can be accessed directly via the
 * preference store.
 */

public class FlipFlopPreferencePage extends FieldEditorPreferencePage implements IWorkbenchPreferencePage {
    public static final String P_CONFIG_FILE = "filePreference";

    public static final String P_CONFIG_ONE_TAG = "configOneTag";

    public static final String P_CONFIG_TWO_TAG = "configTwoTag";
    
    public static final String P_CONFIG_ONE_ICON = "configOneIcon";

    public static final String P_CONFIG_TWO_ICON = "configTwoIcon";

    public static final String P_CUR_CONFIG = "curConfig";

    public FlipFlopPreferencePage() {
        super(GRID);
        setPreferenceStore(FlipFlopPlugin.getDefault().getPreferenceStore());
        setDescription("FlipFlop Plugin Configuration");
    }

    /**
     * Creates the field editors. Field editors are abstractions of the common GUI blocks needed to manipulate various types of preferences. Each field editor
     * knows how to save and restore itself.
     */
    public void createFieldEditors() {
        addField(new FileFieldEditor(P_CONFIG_FILE, "&Config file:", getFieldEditorParent()));
        
        addField(new StringFieldEditor(P_CONFIG_ONE_TAG, "Config&One Tag:", getFieldEditorParent()));
        addField(new StringFieldEditor(P_CONFIG_TWO_TAG, "Config&Two Tag:", getFieldEditorParent()));

        addField(new RadioGroupFieldEditor(
                P_CUR_CONFIG, "Current Configuration", 1,
                new String[][]{{"Config O&ne", "configOne" }, {"Config T&wo", "configTwo"}}, getFieldEditorParent()));

        addField(new FileFieldEditor(P_CONFIG_ONE_ICON, "ConfigOn&e Icon:", getFieldEditorParent()));
        addField(new FileFieldEditor(P_CONFIG_TWO_ICON, "ConfigTw&o Icon:", getFieldEditorParent()));
    }

    public void init(IWorkbench workbench) {
    }
}