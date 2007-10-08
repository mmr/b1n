package org.b1n.ib;

import org.b1n.ib.preferences.PreferencesConstants;
import org.eclipse.jface.preference.IPreferenceStore;
import org.eclipse.jface.preference.PreferenceConverter;
import org.eclipse.swt.graphics.RGB;
import org.eclipse.ui.plugin.AbstractUIPlugin;
import org.osgi.framework.BundleContext;

/**
 * @author Marcio Ribeiro
 * @date 07/10/2007
 */
public class InsertBeautifierPlugin extends AbstractUIPlugin {
    public static final String PLUGIN_ID = "InsertBeautifier";
    private static RGB defaultColorInsert = new RGB(0, 0, 128);
    private static RGB defaultColorComment = new RGB(0, 128, 0);
    private static RGB defaultColorString = new RGB(128, 128, 128);
    private static InsertBeautifierPlugin plugin;

    public void start(BundleContext context) throws Exception {
        super.start(context);
        plugin = this;
        defaultColorInsert = new RGB(0, 0, 128);
        defaultColorComment = new RGB(0, 128, 0);
        defaultColorString = new RGB(128, 128, 128);
    }

    public void stop(BundleContext context) throws Exception {
        plugin = null;
        defaultColorInsert = null;
        defaultColorComment = new RGB(0, 128, 0);
        defaultColorString = new RGB(128, 128, 128);
        super.stop(context);
    }

    public static InsertBeautifierPlugin getDefault() {
        return plugin;
    }

    @Override
    protected void initializeDefaultPreferences(IPreferenceStore store) {
        store.setDefault(PreferencesConstants.MIN_FIELDS, "3");

        PreferenceConverter.setDefault(getPreferenceStore(), PreferencesConstants.COLOR_INSERT, defaultColorInsert);
        PreferenceConverter.setDefault(getPreferenceStore(), PreferencesConstants.COLOR_COMMENT, defaultColorComment);
        PreferenceConverter.setDefault(getPreferenceStore(), PreferencesConstants.COLOR_STRING, defaultColorString);
    }
}