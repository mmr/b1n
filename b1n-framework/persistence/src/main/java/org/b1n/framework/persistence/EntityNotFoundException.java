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

/**
 * Lancada quando entidade buscada nao for encontrada.
 * @author Marcio Ribeiro (mmr)
 * @created Mar 30, 2007
 */
public class EntityNotFoundException extends PersistenceException {
    private Class<? extends Entity> clazz;

    private String query;

    private Long id;

    /**
     * @param clazz the class.
     */
    public EntityNotFoundException(final Class<? extends Entity> clazz) {
        super("Could not find " + clazz.getName());
        this.clazz = clazz;
    }

    /**
     * @param clazz the class.
     * @param id id.
     */
    public EntityNotFoundException(final Class<? extends Entity> clazz, final Long id) {
        super("Could not find " + clazz.getName() + " with id " + id);
        this.clazz = clazz;
        this.id = id;
    }

    /**
     * @param clazz class.
     * @param id id.
     * @param cause the cause.
     */
    public EntityNotFoundException(final Class<? extends Entity> clazz, final Long id, final Throwable cause) {
        super("Could not find " + clazz.getName() + " with id " + id, cause);
        this.clazz = clazz;
        this.id = id;
    }

    /**
     * @param clazz class.
     * @param query query.
     */
    public EntityNotFoundException(final Class<? extends Entity> clazz, final String query) {
        super("Could not find " + clazz.getName() + " for query '" + query + "'.");
        this.clazz = clazz;
        this.query = query;
    }

    /**
     * @param clazz class.
     * @param query query.
     * @param cause cause.
     */
    public EntityNotFoundException(final Class<? extends Entity> clazz, final String query, final Throwable cause) {
        super("Could not find " + clazz.getName() + " for query '" + query + "'.", cause);
        this.clazz = clazz;
        this.query = query;
    }

    /**
     * @return class.
     */
    public Class<? extends Entity> getClazz() {
        return clazz;
    }

    /**
     * @param clazz class.
     */
    public void setClazz(final Class<? extends Entity> clazz) {
        this.clazz = clazz;
    }

    /**
     * @return id.
     */
    public Long getId() {
        return id;
    }

    /**
     * @param id id.
     */
    public void setId(final Long id) {
        this.id = id;
    }

    /**
     * @return query.
     */
    public String getQuery() {
        return query;
    }

    /**
     * @param query query.
     */
    public void setQuery(final String query) {
        this.query = query;
    }
}