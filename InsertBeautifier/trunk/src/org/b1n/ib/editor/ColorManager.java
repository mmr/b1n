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
 * @author Marcio Ribeiro
 * @date 07/10/2007
 */
public class ColorManager {

    protected Map<RGB, Color> colorTable = new HashMap<RGB, Color>();

    public void dispose() {
        for (Color color : colorTable.values()) {
            color.dispose();
        }
    }

    private Color getColor(RGB rgb) {
        Color color = colorTable.get(rgb);
        if (color == null) {
            color = new Color(Display.getCurrent(), rgb);
            colorTable.put(rgb, color);
        }
        return color;
    }

    private Color getConfiguredColor(String confName) {
        IPreferenceStore ps = InsertBeautifierPlugin.getDefault().getPreferenceStore();
        return getColor(PreferenceConverter.getColor(ps, confName));
    }

    public Color getInsertColor() {
        return getConfiguredColor(PreferencesConstants.COLOR_INSERT);
    }

    public Color getCommentColor() {
        return getConfiguredColor(PreferencesConstants.COLOR_COMMENT);
    }

    public Color getStringColor() {
        return getConfiguredColor(PreferencesConstants.COLOR_STRING);
    }
}