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
 * Configuracao do editor.
 * @author Marcio Ribeiro
 * @date 07/10/2007
 */
public class Configuration extends SourceViewerConfiguration {
    /** Procurador de inserts. */
    private InsertScanner insertScanner;

    /** Gerenciador de cores. */
    private ColorManager colorManager;

    /**
     * Construtor.
     * @param colorManager gerenciador de cores.
     */
    public Configuration(final ColorManager colorManager) {
        this.colorManager = colorManager;
    }

    /**
     * Devolve tipos de conteudo possiveis trataveis pelo editor.
     * @param sourceViewer editor.
     * @return tipos de conteudo possiveis trataveis pelo editor.
     */
    @Override
    public String[] getConfiguredContentTypes(final ISourceViewer sourceViewer) {
        return new String[] { IDocument.DEFAULT_CONTENT_TYPE, PartitionScanner.COMMENT, PartitionScanner.INSERT };
    }

    /**
     * Devolve o formatador com as estrategias de formatacao configuradas.
     * @param sourceViewer editor.
     * @return o formatador com as estrategias de formatacao configuradas.
     */
    @Override
    public IContentFormatter getContentFormatter(final ISourceViewer sourceViewer) {
        ContentFormatter formatter = new ContentFormatter();
        formatter.setFormattingStrategy(new DefaultFormattingStrategy(), IDocument.DEFAULT_CONTENT_TYPE);
        formatter.setFormattingStrategy(new InsertFormattingStrategy(), PartitionScanner.INSERT);
        return formatter;
    }

    /**
     * Devolve o reconciliador de apresentacao com os alteradores configurados.
     * @param sourceViewer editor.
     * @return o reconciliador de apresentacao com os alteradores configurados.
     */
    @Override
    public IPresentationReconciler getPresentationReconciler(final ISourceViewer sourceViewer) {
        PresentationReconciler reconciler = new PresentationReconciler();

        DefaultDamagerRepairer dr = new DefaultDamagerRepairer(this.getInsertScanner());
        reconciler.setDamager(dr, PartitionScanner.INSERT);
        reconciler.setRepairer(dr, PartitionScanner.INSERT);

        NonRuleBasedDamagerRepairer ndr = new NonRuleBasedDamagerRepairer(new TextAttribute(this.colorManager.getCommentColor()));
        reconciler.setDamager(ndr, PartitionScanner.COMMENT);
        reconciler.setRepairer(ndr, PartitionScanner.COMMENT);

        return reconciler;
    }

    /**
     * Devolve procurador de inserts.
     * @return procurador de inserts.
     */
    private InsertScanner getInsertScanner() {
        if (this.insertScanner == null) {
            this.insertScanner = new InsertScanner(this.colorManager);
            this.insertScanner.setDefaultReturnToken(new Token(new TextAttribute(this.colorManager.getInsertColor())));
        }

        return this.insertScanner;
    }
}