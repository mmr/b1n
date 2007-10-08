package org.b1n.ib.editor;

import org.eclipse.core.runtime.CoreException;
import org.eclipse.jface.text.IDocument;
import org.eclipse.jface.text.IDocumentPartitioner;
import org.eclipse.jface.text.rules.FastPartitioner;
import org.eclipse.ui.editors.text.FileDocumentProvider;

/**
 * @author Marcio Ribeiro
 * @date 07/10/2007
 */
public class DocumentProvider extends FileDocumentProvider {

    protected IDocument createDocument(Object element) throws CoreException {
        IDocument document = super.createDocument(element);
        if (document != null) {
            IDocumentPartitioner partitioner = new FastPartitioner(new PartitionScanner(), new String[] { PartitionScanner.COMMENT, PartitionScanner.INSERT });
            partitioner.connect(document);
            document.setDocumentPartitioner(partitioner);
        }
        return document;
    }
}