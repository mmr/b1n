package org.b1n.cheater;

/**
 * @author Marcio Ribeiro (mmr)
 * @created Aug 2, 2008
 */
public class RandomicityTest {
    private int trues;

    private int falses;

    private int tests;

    private int n;

    public RandomicityTest(int tests, int n) {
        this.tests = tests;
        this.n = n;
    }

    private boolean test() {
        return ((int) (Math.random() * n)) == 1;
    }

    void run() {
        for (int i = 0; i < tests; i++) {
            if (test()) {
                trues++;
            } else {
                falses++;
            }
        }
    }

    String getRatio() {
        StringBuilder sb = new StringBuilder();
        sb.append("1:" + Math.round(tests / (float) Math.min(trues, falses)));
        sb.append(" to " + (trues < falses ? "TRUES" : "FALSES"));
        return sb.toString();
    }

    public static void main(String[] args) {
        final int tests = 100000;
        final RandomicityTest test = new RandomicityTest(tests, 50);
        test.run();
        System.out.println("TOTAL  : " + tests);
        System.out.println("TRUES  : " + test.trues);
        System.out.println("FALSES : " + test.falses);
        System.out.println("RATIO  : " + test.getRatio());
    }
}
