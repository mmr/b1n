package org.b1n.framework.persistence.test;

import java.util.Map;

/**
 * @author Marcio Ribeiro
 * @date Jan 18, 2009
 */
public interface Config {
    /**
     * @return emf name.
     */
    String getEmfName();

    /**
     * @return config overrides.
     */
    Map<String, String> getEmfConfigOverrides();
}
