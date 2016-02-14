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

import java.util.Date;
import java.util.List;

import org.hibernate.Criteria;
import org.hibernate.criterion.Restrictions;

/**
 * DAO para entidades do tipo TrackedEntity, que possuem datas informativas de inclusao e ultima atualizacao.
 * @author Marcio Ribeiro (mmr)
 * @created Mar 30, 2007
 * @param <E> tipo.
 */
public abstract class TrackedEntityDao<E extends TrackedEntity> extends HibernateEntityDao<E> {
    /**
     * Devolve colecao de entidades para o intervalo de datas de criacao passadas.
     * @param dateAddedStart data de criacao, inicio.
     * @param dateAddedFinish data de criacao, fim.
     * @return colecao de entidades que foram criadas no intervalo de datas passado.
     */
    public List<E> findByDateAdded(final Date dateAddedStart, final Date dateAddedFinish) {
        final Criteria crit = createCriteria();
        crit.add(Restrictions.between("dateAdded", dateAddedStart, dateAddedFinish));
        return findByCriteria(crit);
    }

    /**
     * Devolve colecao de entidades para o intervalo de datas de atualizacao passadas.
     * @param dateLastUpdatedStart data de atualizacao, inicio.
     * @param dateLastUpdatedFinish data de atualizacao, fim.
     * @return colecao de entidades que foram atualizadas no intervalo de datas passado.
     */
    public List<E> findByDateLastUpdated(final Date dateLastUpdatedStart, final Date dateLastUpdatedFinish) {
        final Criteria crit = createCriteria();
        crit.add(Restrictions.between("dateLastUpdated", dateLastUpdatedStart, dateLastUpdatedFinish));
        return findByCriteria(crit);
    }
}