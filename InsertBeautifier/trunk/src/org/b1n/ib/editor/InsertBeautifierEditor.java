package org.b1n.ib.editor;

import org.eclipse.core.runtime.IProgressMonitor;
import org.eclipse.jface.text.source.ISourceViewer;
import org.eclipse.jface.text.source.SourceViewer;
import org.eclipse.ui.editors.text.TextEditor;

/**
 * O Editor :).
 * @author Marcio Ribeiro
 * @date 07/10/2007
 */
public class InsertBeautifierEditor extends TextEditor {

    /** Gerenciador de cores. */
    private ColorManager colorManager;

    /**
     * Construtor.
     */
    public InsertBeautifierEditor() {
        super();
        this.colorManager = new ColorManager();
        this.setSourceViewerConfiguration(new Configuration(this.colorManager));
        this.setDocumentProvider(new DocumentProvider());
    }

    /**
     * Chamado quando o documento sendo editado for salvo.
     * A formatacao é forçada no evento de save para garantir que todo documento sempre esteja formatado.
     * @param progressMonitor barra de progresso.
     */
    @Override
    public void doSave(final IProgressMonitor progressMonitor) {
        ((SourceViewer) this.getSourceViewer()).doOperation(ISourceViewer.FORMAT);
        super.doSave(progressMonitor);
    }

    /**
     * Chamado ao fechar o editor para liberar memoria.
     */
    @Override
    public void dispose() {
        this.colorManager.dispose();
        super.dispose();
    }
}