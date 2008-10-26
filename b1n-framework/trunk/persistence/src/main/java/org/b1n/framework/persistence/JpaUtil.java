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
package org.b1n.framework.persistence;

import java.util.Map;

import javax.persistence.EntityManager;
import javax.persistence.EntityManagerFactory;
import javax.persistence.EntityTransaction;
import javax.persistence.Persistence;

/**
 * Utilitario que cria/fecha as sessoes/transacoes do hibernate.
 * @author Marcio Ribeiro (mmr)
 * @created Mar 29, 2007
 */
public final class JpaUtil {
    private static final ThreadLocal<EntityManager> SESSION = new ThreadLocal<EntityManager>();

    private static EntityManagerFactory sessionFactory;

    /**
     * Classe utilitaria.
     */
    private JpaUtil() {
        // do nothing
    }

    /**
     * @return devolve a sessao corrente (cria uma se for necessario).
     */
    public static EntityManager getSession() {
        return getSession(null);
    }

    /**
     * Cria/Recupera sessao.
     * @param emfName nome de session factory.
     * @return sessao.
     */
    public static EntityManager getSession(final String emfName) {
        return getSession(emfName, null);
    }

    /**
     * Cria/Recupera sessao.
     * @param emfName nome de session factory.
     * @param configOverrides sobrecarregando config.
     * @return sessao.
     */
    public static EntityManager getSession(final String emfName, final Map<String, String> configOverrides) {
        if (sessionFactory == null) {
            sessionFactory = Persistence.createEntityManagerFactory(emfName, configOverrides);
        }

        EntityManager s = SESSION.get();
        if (s == null) {
            s = sessionFactory.createEntityManager();
            final EntityTransaction tr = s.getTransaction();
            tr.begin();
            SESSION.set(s);
        }
        return s;
    }

    /**
     * Fecha a sessao corrente.
     */
    public static void closeSession() {
        EntityManager s = SESSION.get();
        SESSION.set(null);
        if (s != null) {
            if (!s.getTransaction().getRollbackOnly()) {
                s.flush();
                s.getTransaction().commit();
            } else {
                s.getTransaction().rollback();
            }
            s.close();
        }
        s = null;
    }
}