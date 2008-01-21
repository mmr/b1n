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
package org.b1n.framework.persistence.test.entity;

import org.b1n.framework.persistence.DaoLocator;
import org.b1n.framework.persistence.EntityNotFoundException;
import org.b1n.framework.persistence.entity.Doctor;
import org.b1n.framework.persistence.entity.Hospital;
import org.b1n.framework.persistence.entity.HospitalDao;
import org.b1n.framework.persistence.test.PersistenceTestCase;


/**
 * @author Marcio Ribeiro (mmr)
 * @created Mar 28, 2007
 */
public class HospitalTest extends PersistenceTestCase {
    private static long pk;

    private static final HospitalDao hospitalDao = DaoLocator.getDao(Hospital.class);

    private static final String NAME = "Albert Einstein";

    public void testSaveAndLoad() throws Exception {
        // Criando hospital
        Hospital hospital = new Hospital();
        hospital.setName(NAME);

        Doctor doc1 = new Doctor();
        doc1.setName("Fernanda Porto");
        hospital.addDoctor(doc1);

        Doctor doc2 = new Doctor();
        doc2.setName("Marisa Monte");
        hospital.addDoctor(doc2);

        Doctor doc3 = new Doctor();
        doc3.setName("Arnaldo Antunes");
        hospital.addDoctor(doc3);
        hospital.save();
        pk = hospital.getId();

        Hospital loaded = hospitalDao.findById(pk);
        assertEquals(hospital.getName(), loaded.getName());
    }

    public void testGetByName() throws Exception {
        Hospital hospitalByName = hospitalDao.getByName(NAME);
        Hospital hospitalById = hospitalDao.findById(pk);
        assertEquals(hospitalByName.getName(), hospitalById.getName());
    }

    public void testRemove() throws Exception {
        // Carregando
        Hospital hospital = hospitalDao.findById(pk);
        hospital.remove();

        HospitalDao hospitalFac = DaoLocator.getDao(Hospital.class);
        try {
            hospitalFac.findById(pk);
            fail("Nao removeu .");
        } catch (EntityNotFoundException e) {
            // Ok, foi removido.
        }
    }
}