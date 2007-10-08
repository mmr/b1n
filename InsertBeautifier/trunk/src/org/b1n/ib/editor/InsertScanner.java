package org.b1n.ib.editor;

import org.eclipse.jface.text.TextAttribute;
import org.eclipse.jface.text.rules.IRule;
import org.eclipse.jface.text.rules.IToken;
import org.eclipse.jface.text.rules.RuleBasedScanner;
import org.eclipse.jface.text.rules.SingleLineRule;
import org.eclipse.jface.text.rules.Token;
import org.eclipse.jface.text.rules.WhitespaceRule;

/**
 * Buscador de INSERTs. Trata dados em um token de INSERT.
 * @author Marcio Ribeiro
 * @date 07/10/2007
 */
public class InsertScanner extends RuleBasedScanner {

    /**
     * Construtor.
     * @param manager gerenciador de cores.
     */
    public InsertScanner(final ColorManager manager) {
        IToken string = new Token(new TextAttribute(manager.getStringColor()));

        IRule[] rules = new IRule[] {
                new SingleLineRule("'", "'", string, '\\'),
                new WhitespaceRule(new WhitespaceDetector()) };

        this.setRules(rules);
    }
}