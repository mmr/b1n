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

import org.eclipse.jface.text.formatter.IFormattingStrategy;

/**
 * Formatador dummy.
 * @author Marcio Ribeiro
 * @date 07/10/2007
 */
public class DefaultFormattingStrategy implements IFormattingStrategy {

    /**
     * Devolve conteudo formatado.
     * @param content conteudo a ser formatado.
     * @param isLineStart true se for comeco de linha, false se nao.
     * @param indentation endentacao.
     * @param positions posicoes.
     * @return conteudo formatado.
     */
    public String format(final String content, final boolean isLineStart, final String indentation, final int[] positions) {
        return null;
    }

    /**
     * Chamado quando a formatacao iniciar.
     * @param initialIndentation endentacao inicial.
     */
    public void formatterStarts(final String initialIndentation) {
        // do nothing
    }

    /**
     * Chamado quando a formatacao terminar.
     */
    public void formatterStops() {
        // do nothing
    }
}