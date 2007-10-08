package org.b1n.ib.editor;

import org.eclipse.jface.text.IDocument;
import org.eclipse.jface.text.TextAttribute;
import org.eclipse.jface.text.formatter.ContentFormatter;
import org.eclipse.jface.text.formatter.IContentFormatter;
import org.eclipse.jface.text.presentation.IPresentationReconciler;
import org.eclipse.jface.text.presentation.PresentationReconciler;
import org.eclipse.jface.text.rules.DefaultDamagerRepairer;
import org.eclipse.jface.text.rules.Token;
import org.eclipse.jface.text.source.ISourceViewer;
import org.eclipse.jface.text.source.SourceViewerConfiguration;

/**
 * @author Marcio Ribeiro
 * @date 07/10/2007
 */
public class Configuration extends SourceViewerConfiguration {
    private InsertScanner insertScanner;
    private ColorManager colorManager;

    public Configuration(ColorManager colorManager) {
        this.colorManager = colorManager;
    }

    public String[] getConfiguredContentTypes(ISourceViewer sourceViewer) {
        return new String[] { IDocument.DEFAULT_CONTENT_TYPE, PartitionScanner.COMMENT, PartitionScanner.INSERT };
    }

    @Override
    public IContentFormatter getContentFormatter(ISourceViewer sourceViewer) {
        ContentFormatter formatter = new ContentFormatter();
        formatter.setFormattingStrategy(new DefaultFormattingStrategy(), IDocument.DEFAULT_CONTENT_TYPE);
        formatter.setFormattingStrategy(new InsertFormattingStrategy(), PartitionScanner.INSERT);
        return formatter;
    }

    @Override
    public IPresentationReconciler getPresentationReconciler(ISourceViewer sourceViewer) {
        PresentationReconciler reconciler = new PresentationReconciler();

        DefaultDamagerRepairer dr = new DefaultDamagerRepairer(getInsertScanner());
        reconciler.setDamager(dr, PartitionScanner.INSERT);
        reconciler.setRepairer(dr, PartitionScanner.INSERT);

        NonRuleBasedDamagerRepairer ndr = new NonRuleBasedDamagerRepairer(new TextAttribute(colorManager.getCommentColor()));
        reconciler.setDamager(ndr, PartitionScanner.COMMENT);
        reconciler.setRepairer(ndr, PartitionScanner.COMMENT);

        return reconciler;
    }

    private InsertScanner getInsertScanner() {
        if (insertScanner == null) {
            insertScanner = new InsertScanner(colorManager);
            insertScanner.setDefaultReturnToken(new Token(new TextAttribute(colorManager.getInsertColor())));
        }

        return insertScanner;
    }
}