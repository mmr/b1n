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
package b1n.framework.persistence.test.entity;

import b1n.framework.persistence.entity.Doctor;
import b1n.framework.persistence.entity.DoctorFactory;
import b1n.framework.persistence.entity.EntityNotFoundException;
import b1n.framework.persistence.entity.Hospital;
import b1n.framework.persistence.entity.HospitalFactory;
import b1n.framework.persistence.entity.factory.FactoryLocator;
import b1n.framework.persistence.test.PersistenceTestCase;

/**
 * @author Marcio Ribeiro (mmr)
 * @created Mar 28, 2007
 */
public class HospitalTest extends PersistenceTestCase {
    private static long pk;

    private static final HospitalFactory hospitalFac = FactoryLocator.findFactory(Hospital.class);

    private static final DoctorFactory docFac = FactoryLocator.findFactory(Doctor.class);

    private static final String NAME = "Albert Einstein";

    public void testSaveAndLoad() throws Exception {
        // Criando hospital
        Hospital hospital = hospitalFac.createEntity();
        hospital.setName(NAME);

        Doctor doc1 = docFac.createEntity();
        doc1.setName("Fernanda Porto");
        hospital.addDoctor(doc1);

        Doctor doc2 = docFac.createEntity();
        doc2.setName("Marisa Monte");
        hospital.addDoctor(doc2);

        Doctor doc3 = docFac.createEntity();
        doc3.setName("Arnaldo Antunes");
        hospital.addDoctor(doc3);
        hospital.save();
        pk = hospital.getId();

        Hospital loaded = hospitalFac.findById(pk);
        assertEquals(hospital.getName(), loaded.getName());
    }

    public void testGetByName() throws Exception {
        Hospital hospitalByName = hospitalFac.getByName(NAME);
        Hospital hospitalById = hospitalFac.findById(pk);
        assertEquals(hospitalByName.getName(), hospitalById.getName());
    }

    public void testRemove() throws Exception {
        // Carregando
        Hospital hospital = hospitalFac.findById(pk);
        hospital.remove();

        HospitalFactory hospitalFac = FactoryLocator.findFactory(Hospital.class);
        try {
            hospitalFac.findById(pk);
            fail("Nao removeu .");
        } catch (EntityNotFoundException e) {
            // Ok, foi removido.
        }
    }
}