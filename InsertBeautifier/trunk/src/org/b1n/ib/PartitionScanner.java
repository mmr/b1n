package org.b1n.ib;

import org.eclipse.jface.text.rules.IPredicateRule;
import org.eclipse.jface.text.rules.IToken;
import org.eclipse.jface.text.rules.MultiLineRule;
import org.eclipse.jface.text.rules.RuleBasedPartitionScanner;
import org.eclipse.jface.text.rules.SingleLineRule;
import org.eclipse.jface.text.rules.Token;

/**
 * @author Marcio Ribeiro
 * @date 07/10/2007
 */
public class PartitionScanner extends RuleBasedPartitionScanner {
    public final static String COMMENT = "__comment";
    public final static String INSERT = "__insert";

    public PartitionScanner() {
        IToken comment = new Token(COMMENT);
        IToken insert = new Token(INSERT);

        IPredicateRule[] rules = new IPredicateRule[]{
                new SingleLineRule("--", null, comment),
                new MultiLineRule("/*", "*/", comment),
                new MultiLineRule("INSERT", ";", insert),
                new MultiLineRule("insert", ";", insert)};

        setPredicateRules(rules);
    }
}