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
package b1n.framework.persistence.bo.factory;

import java.util.Date;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

import b1n.framework.persistence.bo.EntityNotFoundException;
import b1n.framework.persistence.bo.SimpleEntity;

/**
 * @author Marcio Ribeiro (mmr)
 * @created Mar 30, 2007
 */
public abstract class SimpleEntityFactory<BoClass extends SimpleEntity> extends JpaEntityFactory<BoClass> {
    public List<BoClass> getByDateAdded(Date dateAddedStart, Date dateAddedFinish) throws EntityNotFoundException {
        Map<String, Date> params = new HashMap<String, Date>();
        params.put("dateAddedStart", dateAddedStart);
        params.put("dateAddedFinish", dateAddedFinish);
        return getByQuery("SELECT bo FROM " + getBoClass().getName() + " WHERE bo.dataAdded BETWEEN :dateAddedStart and :dateAddedFinish", params);
    }

    public List<BoClass> getByDateLastUpdated(Date dateLastUpdatedStart, Date dateLastUpdatedFinish) throws EntityNotFoundException {
        Map<String, Date> params = new HashMap<String, Date>();
        params.put("dateLastUpdatedStart", dateLastUpdatedStart);
        params.put("dateLastUpdatedFinish", dateLastUpdatedFinish);
        return getByQuery("SELECT bo FROM " + getBoClass().getName() + " WHERE bo.dateLastUpdated BETWEEN :dateLastUpdatedStart and :dateLastUpdatedFinish",
                params);
    }
}