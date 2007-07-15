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
package b1n.framework.persistence.entity;

import java.io.Serializable;

import b1n.framework.persistence.PersistenceException;

/**
 * @author Marcio Ribeiro (mmr)
 * @created Mar 30, 2007
 */
public class EntityNotFoundException extends PersistenceException {
    private Class<? extends Entity> clazz;

    private String query;

    private Long id;

    public EntityNotFoundException(Class<? extends Entity> clazz, Long id) {
        super("Could not find " + clazz.getName() + " with id " + id);
        this.clazz = clazz;
        this.id = id;
    }

    public EntityNotFoundException(Class<? extends Entity> clazz, Long id, Throwable cause) {
        super("Could not find " + clazz.getName() + " with id " + id, cause);
        this.clazz = clazz;
        this.id = id;
    }

    public EntityNotFoundException(Class<? extends Entity> clazz, String query) {
        super("Could not find " + clazz.getName() + " for query '" + query + "'.");
        this.clazz = clazz;
        this.query = query;
    }

    public EntityNotFoundException(Class<? extends Entity> clazz, String query, Throwable cause) {
        super("Could not find " + clazz.getName() + " for query '" + query + "'.", cause);
        this.clazz = clazz;
        this.query = query;
    }

    public Class<? extends Entity> getClazz() {
        return clazz;
    }

    public void setClazz(Class<? extends Entity> clazz) {
        this.clazz = clazz;
    }

    public Serializable getId() {
        return id;
    }

    public void setId(Long id) {
        this.id = id;
    }

    public String getQuery() {
        return query;
    }

    public void setQuery(String query) {
        this.query = query;
    }
}