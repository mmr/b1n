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

import org.eclipse.jface.text.BadLocationException;
import org.eclipse.jface.text.DocumentEvent;
import org.eclipse.jface.text.IDocument;
import org.eclipse.jface.text.IRegion;
import org.eclipse.jface.text.ITypedRegion;
import org.eclipse.jface.text.Region;
import org.eclipse.jface.text.TextAttribute;
import org.eclipse.jface.text.TextPresentation;
import org.eclipse.jface.text.presentation.IPresentationDamager;
import org.eclipse.jface.text.presentation.IPresentationRepairer;
import org.eclipse.core.runtime.Assert;
import org.eclipse.swt.custom.StyleRange;

/**
 * Reparador para comentarios (classe pega em exemplo).
 * @author Marcio Ribeiro
 * @date 08/10/2007
 */
public class NonRuleBasedDamagerRepairer implements IPresentationDamager, IPresentationRepairer {

    /** The document this object works on. */
    private IDocument fDocument;
    /** The default text attribute if non is returned as data by the current token. */
    private TextAttribute fDefaultTextAttribute;

    /**
     * Constructor for NonRuleBasedDamagerRepairer.
     * @param defaultTextAttribute default text attribute.
     */
    public NonRuleBasedDamagerRepairer(final TextAttribute defaultTextAttribute) {
        Assert.isNotNull(defaultTextAttribute);

        this.fDefaultTextAttribute = defaultTextAttribute;
    }

    /**
     * @param document document.
     * @see IPresentationRepairer#setDocument(IDocument)
     */
    public void setDocument(final IDocument document) {
        this.fDocument = document;
    }

    /**
     * Returns the end offset of the line that contains the specified offset or if the offset is inside a line delimiter, the end offset of the next line.
     * @param offset the offset whose line end offset must be computed
     * @return the line end offset for the given offset
     * @exception BadLocationException if offset is invalid in the current document
     */
    protected int endOfLineOf(final int offset) throws BadLocationException {

        IRegion info = this.fDocument.getLineInformationOfOffset(offset);
        if (offset <= info.getOffset() + info.getLength()) {
            return info.getOffset() + info.getLength();
        }

        int line = this.fDocument.getLineOfOffset(offset);
        try {
            info = this.fDocument.getLineInformation(line + 1);
            return info.getOffset() + info.getLength();
        } catch (BadLocationException x) {
            return this.fDocument.getLength();
        }
    }

    /**
     * @param partition the partition.
     * @param event the event.
     * @param documentPartitioningChanged true if the partition changed, false if not.
     * @return the damage region.
     */
    public IRegion getDamageRegion(final ITypedRegion partition, final DocumentEvent event, final boolean documentPartitioningChanged) {
        if (!documentPartitioningChanged) {
            try {

                IRegion info = this.fDocument.getLineInformationOfOffset(event.getOffset());
                int start = Math.max(partition.getOffset(), info.getOffset());

                int len = 0;
                if (event.getText() == null) {
                    len = event.getLength();
                } else {
                    len = event.getText().length();
                }
                int end = event.getOffset() + len;

                if ((info.getOffset() <= end) && (end <= info.getOffset() + info.getLength())) {
                    // optimize the case of the same line
                    end = info.getOffset() + info.getLength();
                } else {
                    end = this.endOfLineOf(end);
                }

                end = Math.min(partition.getOffset() + partition.getLength(), end);
                return new Region(start, end - start);

            } catch (BadLocationException x) {
                // do nothing
            }
        }

        return partition;
    }

    /**
     * @param presentation the presentation.
     * @param region the region.
     * @see IPresentationRepairer#createPresentation(TextPresentation, ITypedRegion)
     */
    public void createPresentation(final TextPresentation presentation, final ITypedRegion region) {
        this.addRange(presentation, region.getOffset(), region.getLength(), this.fDefaultTextAttribute);
    }

    /**
     * Adds style information to the given text presentation.
     * @param presentation the text presentation to be extended.
     * @param offset the offset of the range to be styled.
     * @param length the length of the range to be styled.
     * @param attr the attribute describing the style of the range to be styled.
     */
    protected void addRange(final TextPresentation presentation, final int offset, final int length, final TextAttribute attr) {
        if (attr != null) {
            presentation.addStyleRange(new StyleRange(offset, length, attr.getForeground(), attr.getBackground(), attr.getStyle()));
        }
    }
}