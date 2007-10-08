package org.b1n.ib.editor;

import org.eclipse.core.runtime.IProgressMonitor;
import org.eclipse.jface.text.source.SourceViewer;
import org.eclipse.ui.editors.text.TextEditor;

/**
 * @author Marcio Ribeiro
 * @date 07/10/2007
 */
public class InsertBeautifierEditor extends TextEditor {

    private ColorManager colorManager;

    public InsertBeautifierEditor() {
        super();
        colorManager = new ColorManager();
        setSourceViewerConfiguration(new Configuration(colorManager));
        setDocumentProvider(new DocumentProvider());
    }

    // @Override
    // protected void editorSaved() {
    // ((SourceViewer) getSourceViewer()).doOperation(SourceViewer.FORMAT);
    // super.editorSaved();
    //    }

    @Override
    public void doSave(IProgressMonitor progressMonitor) {
        ((SourceViewer) getSourceViewer()).doOperation(SourceViewer.FORMAT);
        super.doSave(progressMonitor);
    }

    public void dispose() {
        colorManager.dispose();
        super.dispose();
    }
}
