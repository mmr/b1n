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