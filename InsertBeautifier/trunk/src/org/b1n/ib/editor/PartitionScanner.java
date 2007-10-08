package org.b1n.ib.editor;

import org.eclipse.jface.text.rules.IPredicateRule;
import org.eclipse.jface.text.rules.IToken;
import org.eclipse.jface.text.rules.MultiLineRule;
import org.eclipse.jface.text.rules.RuleBasedPartitionScanner;
import org.eclipse.jface.text.rules.SingleLineRule;
import org.eclipse.jface.text.rules.Token;

/**
 * Separador de conteudo. Separa INSERT e COMMENT do resto do conteudo.
 * @author Marcio Ribeiro
 * @date 07/10/2007
 */
public class PartitionScanner extends RuleBasedPartitionScanner {
    /** Marcador para comentario. */
    public static final String COMMENT = "__comment";

    /** Marcador para insert. */
    public static final String INSERT = "__insert";

    /**
     * Construtor.
     */
    public PartitionScanner() {
        IToken comment = new Token(PartitionScanner.COMMENT);
        IToken insert = new Token(PartitionScanner.INSERT);

        IPredicateRule[] rules = new IPredicateRule[] { new SingleLineRule("--", null, comment), new MultiLineRule("/*", "*/", comment), new MultiLineRule("INSERT", ";", insert), new MultiLineRule("insert", ";", insert) };

        this.setPredicateRules(rules);
    }
}