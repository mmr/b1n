package org.b1n.receiver.web;

import java.io.IOException;

import javax.servlet.Filter;
import javax.servlet.FilterChain;
import javax.servlet.FilterConfig;
import javax.servlet.ServletException;
import javax.servlet.ServletRequest;
import javax.servlet.ServletResponse;

import b1n.framework.persistence.JpaUtil;

/**
 * @author Marcio Ribeiro
 * @date Jan 20, 2008
 */
public class TransactionFilter implements Filter {

    /**
     * Aplica filtro.
     * @param req request.
     * @param resp response.
     * @throws IOException caso algo de inesperado ocorra.
     * @throws ServletException caso algo de inesperado ocorra.
     */
    public void doFilter(ServletRequest req, ServletResponse resp, FilterChain chain) throws IOException, ServletException {
        JpaUtil.getSession();
        chain.doFilter(req, resp);
        JpaUtil.closeSession();
    }

    /**
     * Destroy.
     */
    public void destroy() {
        // do nothing
    }

    /**
     * Init.
     */
    public void init(FilterConfig arg0) throws ServletException {
        // do nothing
    }

}
