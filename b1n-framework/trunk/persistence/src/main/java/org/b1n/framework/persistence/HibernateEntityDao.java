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

/**
 * @author Marcio Ribeiro (mmr)
 * @created Mar 28, 2007
 * @param <E> tipo.
 */
public abstract class HibernateEntityDao<E extends JpaEntity> extends JpaEntityDao<E> {
    /**
     * Devolve colecao de entidades encontradas com criteria passado.
     * @param criteria criteria.
     * @return colecao de entidades encontadas.
     */
    @SuppressWarnings("unchecked")
    protected List<E> findByCriteria(Criteria criteria) {
        return (List<E>) criteria.list();
    }

    /**
     * Devolve entidade encontrada para criteria passado.
     * @param criteria criteria.
     * @return a entidade encontrada.
     * @throws EntityNotFoundException caso nao encontre uma entidade.
     */
    @SuppressWarnings("unchecked")
    protected E findByCriteriaSingle(Criteria criteria) throws EntityNotFoundException {
        E entity = (E) criteria.uniqueResult();
        if (entity == null) {
            throw new EntityNotFoundException(getEntityClass());
        }
        return entity;
    }

    /**
     * @return uma criteria para esse tipo de entidade.
     */
    protected Criteria createCriteria() {
        return ((Session) JpaUtil.getSESSION().getDelegate()).createCriteria(getEntityClass());
    }

    /**
     * @return todas entidades desse tipo.
     */
    public List<E> findAll() {
        return findByCriteria(createCriteria());
    }
}