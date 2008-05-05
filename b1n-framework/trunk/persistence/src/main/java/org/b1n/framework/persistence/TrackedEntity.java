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

import javax.persistence.Column;
import javax.persistence.MappedSuperclass;
import javax.persistence.PrePersist;

/**
 * Entidade com datas informativas de criacao e ultima atualizacao.
 * @author Marcio Ribeiro (mmr)
 * @created Mar 30, 2007
 */
@MappedSuperclass
public abstract class TrackedEntity extends JpaEntity {
    @Column(nullable = false)
    private Date dateAdded;

    private Date dateLastUpdated;

    /**
     * @return a data em que entidade foi criada.
     */
    public Date getDateAdded() {
        return dateAdded;
    }

    /**
     * @param dateAdded data em que foi criado.
     */
    public void setDateAdded(final Date dateAdded) {
        this.dateAdded = dateAdded;
    }

    /**
     * @return data de ultima atualizacao.
     */
    public Date getDateLastUpdated() {
        return dateLastUpdated;
    }

    /**
     * @param dateLastUpdated data de ultima atualizacao.
     */
    public void setDateLastUpdated(final Date dateLastUpdated) {
        this.dateLastUpdated = dateLastUpdated;
    }

    /**
     * chamado antes de persistir o bo.
     */
    @PrePersist
    protected void onSave() {
        if (dateAdded == null) {
            dateAdded = new Date();
        } else if (getId() != null && dateLastUpdated == null) {
            dateLastUpdated = new Date();
        }
    }
}