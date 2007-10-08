package org.b1n.ib.editor;

import org.eclipse.core.runtime.CoreException;
import org.eclipse.jface.text.IDocument;
import org.eclipse.jface.text.IDocumentPartitioner;
import org.eclipse.jface.text.rules.FastPartitioner;
import org.eclipse.ui.editors.text.FileDocumentProvider;

/**
 * Provedor de documentos.
 * @author Marcio Ribeiro
 * @date 07/10/2007
 */
public class DocumentProvider extends FileDocumentProvider {

    /**
     * Devolve documento com particoes especificas (INSERT e COMMENT) configuradas.
     * @param element elemento passado pelo project explorer.
     * @return documento com particoes especificas (INSERT e COMMENT) configuradas.
     * @throws CoreException caso algo de inesperado ocorra ao criar documento.
     */
    @Override
    protected IDocument createDocument(final Object element) throws CoreException {
        IDocument document = super.createDocument(element);
        if (document != null) {
            IDocumentPartitioner partitioner = new FastPartitioner(new PartitionScanner(), new String[] { PartitionScanner.COMMENT, PartitionScanner.INSERT });
            partitioner.connect(document);
            document.setDocumentPartitioner(partitioner);
        }
        return document;
    }
}