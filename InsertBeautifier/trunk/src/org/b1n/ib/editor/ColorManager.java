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