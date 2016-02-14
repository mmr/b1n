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

import java.lang.reflect.ParameterizedType;
import java.util.List;
import java.util.Map;

import javax.persistence.NoResultException;
import javax.persistence.PersistenceException;
import javax.persistence.Query;

/**
 * DAO base para entidades JPA.
 * @author Marcio Ribeiro (mmr)
 * @created Mar 28, 2007
 * @param <E> tipo.
 */
public abstract class JpaEntityDao<E extends JpaEntity> implements EntityDao<E> {
    private Class<E> entityClass;

    /**
     * @param id id.
     * @return entidade com o id passada.
     * @throws EntityNotFoundException caso nao encontre entidade alguma.
     */
    public E findById(final Long id) throws EntityNotFoundException {
        final E entity = JpaUtil.getSession().find(getEntityClass(), id);
        if (entity == null) {
            throw new EntityNotFoundException(getEntityClass(), id);
        }
        return entity;
    }

    /**
     * Encontra entidade por query passada.
     * @param query a query.
     * @return entidade encontrada.
     * @throws EntityNotFoundException caso nao encontre entidade alguma.
     */
    protected E findByQuerySingle(final String query) throws EntityNotFoundException {
        return findByQuerySingle(query, null);
    }

    /**
     * Devolve a entidade encontrada para a query passada.
     * @param query query.
     * @param params mapa com parametros.
     * @return a entidade encontrada.
     * @throws EntityNotFoundException caso nao encontre uma entidade.
     */
    @SuppressWarnings("unchecked")
    protected E findByQuerySingle(final String query, final Map<String, ?> params) throws EntityNotFoundException {
        try {
            return (E) createJpaQuery(query, params).getSingleResult();
        } catch (final NoResultException e) {
            throw new EntityNotFoundException(getEntityClass(), query, e);
        }
    }

    /**
     * @param query query.
     * @return colecao com entidades para a query passada.
     */
    protected List<E> findByQuery(final String query) {
        return findByQuery(query, null);
    }

    /**
     * @param query query.
     * @param params parametros.
     * @return colecao com entidades encontradas para query passada.
     */
    @SuppressWarnings("unchecked")
    protected List<E> findByQuery(final String query, final Map<String, ?> params) {
        return createJpaQuery(query, params).getResultList();
    }

    /**
     * @return classe de entidade.
     */
    @SuppressWarnings("unchecked")
    protected Class<E> getEntityClass() {
        try {
            if (entityClass == null) {
                entityClass = (Class<E>) ((ParameterizedType) getClass().getGenericSuperclass()).getActualTypeArguments()[0];
            }
            return entityClass;
        } catch (final ClassCastException e) {
            throw new PersistenceException(e);
        }
    }

    /**
     * Metodo utilitario que cria query jpa a partir de string com query e mapa de parametros passados.
     * @param query query.
     * @param params parametros.
     * @return query jpa.
     */
    private Query createJpaQuery(final String query, final Map<String, ?> params) {
        final Query jpaQuery = JpaUtil.getSession().createQuery(query);

        if (params != null) {
            for (final Map.Entry<String, ?> entry : params.entrySet()) {
                jpaQuery.setParameter(entry.getKey(), entry.getValue());
            }
        }

        return jpaQuery;
    }
}