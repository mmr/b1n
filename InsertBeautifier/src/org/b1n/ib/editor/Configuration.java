/* Copyright (c) 2007, B1N.ORG
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *     * Redistributions of source code must retain the above copyright
 *       notice, this list of conditions and the following disclaimer.
 *     * Redistributions in binary form must reproduce the above copyright
 *       notice, this list of conditions and the following disclaimer in the
 *       documentation and/or other materials provided with the distribution.
 *     * Neither the name of the B1N.ORG organization nor the
 *       names of its contributors may be used to endorse or promote products
 *       derived from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS "AS IS" AND ANY
 * EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED. IN NO EVENT SHALL B1N.ORG OR ITS CONTRIBUTORS BE LIABLE FOR ANY
 * DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
 * ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 */
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