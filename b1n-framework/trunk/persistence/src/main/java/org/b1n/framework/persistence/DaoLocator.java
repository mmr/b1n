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

import java.util.HashMap;
import java.util.Map;

/**
 * Encontrador de DAOs.
 * @author Marcio Ribeiro (mmr)
 * @created Mar 28, 2007
 */
public final class DaoLocator {
    /** Cache. */
    private static final Map<String, EntityDao<Entity>> CACHE = new HashMap<String, EntityDao<Entity>>();

    /**
     * This class should not be instantiated.
     */
    private DaoLocator() {
        // do nothing
    }

    /**
     * Find the DAO for the passed entity class.
     * @param <T> type.
     * @param entityClass the class of the entity.
     * @return the DAO for the passed entity class.
     */
    @SuppressWarnings("unchecked")
    public static <T extends EntityDao> T getDao(final Class<? extends Entity> entityClass) {
        try {
            final String daoClassName = entityClass.getName() + "Dao";
            if (CACHE.containsKey(daoClassName)) {
                return (T) CACHE.get(daoClassName);
            }
            final T dao = (T) Class.forName(daoClassName).newInstance();
            CACHE.put(daoClassName, dao);
            return dao;
        } catch (final InstantiationException e) {
            throw new CouldNotFindDaoException(e);
        } catch (final IllegalAccessException e) {
            throw new CouldNotFindDaoException(e);
        } catch (final ClassNotFoundException e) {
            throw new CouldNotFindDaoException(e);
        }
    }
}