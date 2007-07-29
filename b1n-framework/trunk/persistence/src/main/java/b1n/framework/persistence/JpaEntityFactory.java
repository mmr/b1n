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
package b1n.framework.persistence;

import java.lang.reflect.ParameterizedType;
import java.util.List;
import java.util.Map;

import javax.persistence.NoResultException;
import javax.persistence.PersistenceException;
import javax.persistence.Query;

/**
 * @author Marcio Ribeiro (mmr)
 * @created Mar 28, 2007
 */
public abstract class JpaEntityFactory<E extends JpaEntity> implements EntityFactory<E> {
    private Class<E> entityClass;

    public E createEntity() {
        try {
            return getEntityClass().newInstance();
        } catch (InstantiationException e) {
            throw new PersistenceException(e);
        } catch (IllegalAccessException e) {
            throw new PersistenceException(e);
        }
    }

    public E findById(Long id) throws EntityNotFoundException {
        E entity = JpaUtil.getSession().find(getEntityClass(), id);
        if (entity == null) {
            throw new EntityNotFoundException(getEntityClass(), id);
        }
        return entity;
    }

    protected E findByQuerySingle(String query) throws EntityNotFoundException {
        return findByQuerySingle(query, null);
    }

    @SuppressWarnings("unchecked")
    protected E findByQuerySingle(String query, Map<String, ?> params) throws EntityNotFoundException {
        try {
            return (E) createJpaQuery(query, params).getSingleResult();
        } catch (NoResultException e) {
            throw new EntityNotFoundException(getEntityClass(), query, e);
        }
    }

    protected List<E> findByQuery(String query) {
        return findByQuery(query, null);
    }

    @SuppressWarnings("unchecked")
    protected List<E> findByQuery(String query, Map<String, ?> params) {
        return createJpaQuery(query, params).getResultList();
    }

    protected Class<E> getEntityClass() {
        try {
            if (entityClass == null) {
                entityClass = (Class<E>) ((ParameterizedType) getClass().getGenericSuperclass()).getActualTypeArguments()[0];
            }
            return entityClass;
        } catch (ClassCastException e) {
            throw new PersistenceException(e);
        }
    }

    private Query createJpaQuery(String query, Map<String, ?> params) {
        Query jpaQuery = JpaUtil.getSession().createQuery(query);

        if (params != null) {
            for (Map.Entry<String, ?> entry : params.entrySet()) {
                jpaQuery.setParameter(entry.getKey(), entry.getValue());
            }
        }

        return jpaQuery;
    }
}