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

import java.util.List;

import org.hibernate.Criteria;
import org.hibernate.Session;
import org.hibernate.criterion.Projections;

/**
 * Classe base para entidade persistente do hibernate, usando recursos especificos do hibernate (Criteria).
 * @author Marcio Ribeiro (mmr)
 * @created Mar 28, 2007
 * @param <E> tipo.
 */
public abstract class HibernateEntityDao<E extends JpaEntity> extends JpaEntityDao<E> {
    /**
     * @param criteria criteria.
     * @return collection of the entities found for the criteria.
     */
    @SuppressWarnings("unchecked")
    protected List<E> findByCriteria(final Criteria criteria) {
        return criteria.list();
    }

    /**
     * @param criteria criteria.
     * @return entity found for the criteria.
     * @throws EntityNotFoundException in case an entity could not be found.
     */
    @SuppressWarnings("unchecked")
    protected E findByCriteriaSingle(final Criteria criteria) throws EntityNotFoundException {
        final E entity = (E) criteria.uniqueResult();
        if (entity == null) {
            throw new EntityNotFoundException(getEntityClass());
        }
        return entity;
    }

    /**
     * @return a criteria.
     */
    protected Criteria createCriteria() {
        return ((Session) JpaUtil.getSession().getDelegate()).createCriteria(getEntityClass());
    }

    /**
     * @return all entities of this kind.
     */
    public List<E> findAll() {
        return findByCriteria(createCriteria());
    }

    /**
     * @return entities count.
     */
    public Integer getCount() {
        final Criteria crit = createCriteria();
        crit.setProjection(Projections.rowCount());
        return (Integer) crit.uniqueResult();
    }
}
