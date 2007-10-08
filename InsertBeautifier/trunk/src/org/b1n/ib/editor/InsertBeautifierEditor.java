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