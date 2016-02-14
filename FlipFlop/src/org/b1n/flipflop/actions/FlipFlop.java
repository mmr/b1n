package org.b1n.flipflop.actions;

import java.io.BufferedReader;
import java.io.FileReader;
import java.io.FileWriter;
import java.io.IOException;

import org.b1n.flipflop.FlipFlopPlugin;
import org.b1n.flipflop.preferences.FlipFlopPreferencePage;
import org.eclipse.jface.action.IAction;
import org.eclipse.jface.dialogs.MessageDialog;
import org.eclipse.jface.preference.IPreferenceStore;
import org.eclipse.jface.resource.ImageDescriptor;
import org.eclipse.jface.viewers.ISelection;
import org.eclipse.ui.IWorkbenchWindow;
import org.eclipse.ui.IWorkbenchWindowActionDelegate;

/**
 * FlipFlop the configurations. Called when user clicks the FlipFlop button in toolbar.
 */
public class FlipFlop implements IWorkbenchWindowActionDelegate {
    private IWorkbenchWindow window;

    /**
     * The constructor.
     */
    public FlipFlop() {
    }

    /**
     * The action has been activated. The argument of the method represents the 'real' action sitting in the workbench UI.
     * @see IWorkbenchWindowActionDelegate#run
     */
    public void run(IAction action) {

        try {
            // Reading configuration
            IPreferenceStore config = FlipFlopPlugin.getDefault().getPreferenceStore();

            String fileName = config.getString(FlipFlopPreferencePage.P_CONFIG_FILE);
            String configOne = config.getString(FlipFlopPreferencePage.P_CONFIG_ONE_TAG);
            String configTwo = config.getString(FlipFlopPreferencePage.P_CONFIG_TWO_TAG);
            String configOneIcon = config.getString(FlipFlopPreferencePage.P_CONFIG_ONE_ICON);
            String configTwoIcon = config.getString(FlipFlopPreferencePage.P_CONFIG_TWO_ICON);
            String curConfig = config.getString(FlipFlopPreferencePage.P_CUR_CONFIG);
            curConfig = curConfig.equals("configOne") ? configOne : configTwo;

            // Reading file to FlipFlop
            BufferedReader reader = new BufferedReader(new FileReader(fileName));
            StringBuffer data = new StringBuffer();
            String buffer = reader.readLine();

            // FlipFlopping data
            while (buffer != null) {
                data.append(buffer + "\n");

                if (buffer.indexOf(configOne) != -1) {
                    data.append(doBusiness(buffer, configOne, curConfig, reader));
                } else if (buffer.indexOf(configTwo) != -1) {
                    data.append(doBusiness(buffer, configTwo, curConfig, reader));
                }

                buffer = reader.readLine();
            }
            reader.close();

            // FlipFlopping Config and Icon
            String icon = null;
            if (curConfig.equals(configOne)) {
                curConfig = "configTwo";
                icon = configTwoIcon;
            } else {
                curConfig = "configOne";
                icon = configOneIcon;
            }
            config.setValue(FlipFlopPreferencePage.P_CUR_CONFIG, curConfig);
            action.setImageDescriptor(ImageDescriptor.createFromFile(null, icon));

            // Saving FlipFlopped file
            saveFile(fileName, data.toString());

            // Saving correct icon to plugin.properties, so it starts up correctly next time
            //saveIconChange(icon);
        } catch (Throwable t) {
            /*
            StringWriter stringWriter = new StringWriter();
            PrintWriter printWriter = new PrintWriter(stringWriter);
            t.printStackTrace(printWriter);
            MessageDialog.openError(window.getShell(), "Faz direito que funciona!", "Configure o Plugin antes de usar.\n" + stringWriter.toString());
            */
            MessageDialog.openError(window.getShell(), "Faz direito que funciona!", "Configure o Plugin antes de usar.\nWindow > Preferences > FlipFlop");
            t.printStackTrace();
        }
    }

    /**
     * @param icon
     * @throws IOException
     * @throws FileNotFoundException
     */
    /*
    private void saveIconChange(String icon) throws IOException, FileNotFoundException {
        String pluginDir = Platform.resolve(FlipFlopPlugin.getDefault().getBundle().getEntry("/")).getPath();
        String fileName = pluginDir + "plugin.properties";
        BufferedReader reader = new BufferedReader(new FileReader(fileName));
        StringBuffer data = new StringBuffer();
        String buffer = reader.readLine();

        while (buffer != null) {
            if (buffer.indexOf("curIcon") != -1) {
                buffer = "curIcon = " + icon;
            }
            data.append(buffer + "\n");
            buffer = reader.readLine();
        }
        reader.close();

        FileWriter writer = new FileWriter(fileName);
        writer.write(data.toString());
        writer.close();
    }
    */

    /**
     * Selection in the workbench has been changed. We can change the state of the 'real' action here if we want, but this can only happen after the delegate
     * has been created.
     * @see IWorkbenchWindowActionDelegate#selectionChanged
     */
    public void selectionChanged(IAction action, ISelection selection) {
    }

    /**
     * We can use this method to dispose of any system resources we previously allocated.
     * @see IWorkbenchWindowActionDelegate#dispose
     */
    public void dispose() {
    }

    /**
     * We will cache window object in order to be able to provide parent shell for the message dialog.
     * @see IWorkbenchWindowActionDelegate#init
     */
    public void init(IWorkbenchWindow window) {
        this.window = window;
    }

    /**
     * @param fileName absolute path to file to flipFlop.
     * @param data flipFlopped data to write to file.
     * @throws IOException if could not write to file.
     */
    private void saveFile(String fileName, String data) throws IOException {
        FileWriter writer = new FileWriter(fileName);
        writer.write(data);
        writer.close();
    }

    /**
     * FlipFlop Configurations.
     * @param buffer data from file to flipFlop.
     * @param config configuration to consider.
     * @param curConfig current configuration.
     * @param reader reader for file to flipFlop.
     * @return returns a String with the flipflopped data.
     * @throws IOException
     */
    private String doBusiness(String buffer, String config, String curConfig, BufferedReader reader) throws IOException {
        StringBuffer data = new StringBuffer();
        String comment = buffer.substring(0, buffer.indexOf(config));
        buffer = reader.readLine();

        while (buffer != null) {
            if (buffer.indexOf(config) != -1) {
                data.append(buffer + "\n");
                break;
            }

            if (buffer.length() > comment.length()) {
                if (curConfig.equals(config)) {
                    data.append(comment);
                } else {
                    buffer = buffer.substring(comment.length());
                }
            } else {
                buffer = "";
            }
            data.append(buffer + "\n");
            buffer = reader.readLine();
        }

        return data.toString();
    }
}