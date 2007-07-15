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
package b1n.framework.persistence.test.bo;

import b1n.framework.persistence.bo.EntityNotFoundException;
import b1n.framework.persistence.bo.DoctorBo;
import b1n.framework.persistence.bo.HospitalBo;
import b1n.framework.persistence.bo.factory.FactoryLocator;
import b1n.framework.persistence.bo.factory.DoctorBoFactory;
import b1n.framework.persistence.bo.factory.HospitalBoFactory;
import b1n.framework.persistence.test.PersistenceTestCase;

/**
 * @author Marcio Ribeiro (mmr)
 * @created Mar 28, 2007
 */
public class HospitalBoTest extends PersistenceTestCase {
    private static long pk;

    private static final HospitalBoFactory hospitalFac = FactoryLocator.findFactory(HospitalBo.class);

    private static final DoctorBoFactory docFac = FactoryLocator.findFactory(DoctorBo.class);

    private static final String NAME = "Albert Einstein";

    public void testSaveAndLoad() throws Exception {
        // Criando hospital
        HospitalBo hospital = hospitalFac.getBo();
        hospital.setName(NAME);

        DoctorBo doc1 = docFac.getBo();
        doc1.setName("Fernanda Porto");
        hospital.addDoctor(doc1);

        DoctorBo doc2 = docFac.getBo();
        doc2.setName("Marisa Monte");
        hospital.addDoctor(doc2);

        DoctorBo doc3 = docFac.getBo();
        doc3.setName("Arnaldo Antunes");
        hospital.addDoctor(doc3);
        hospital.save();
        pk = hospital.getId();

        HospitalBo loadedBo = hospitalFac.getBo(pk);
        assertEquals(hospital.getName(), loadedBo.getName());
    }

    public void testGetByName() throws Exception {
        HospitalBo hospitalByName = hospitalFac.getByName(NAME);
        HospitalBo hospitalById = hospitalFac.getBo(pk);
        assertEquals(hospitalByName.getName(), hospitalById.getName());
    }

    public void testRemove() throws Exception {
        // Carregando Bo
        HospitalBo hospital = hospitalFac.getBo(pk);
        hospital.remove();

        HospitalBoFactory hospitalFac = FactoryLocator.findFactory(HospitalBo.class);
        try {
            hospitalFac.getBo(pk);
            fail("Nao removeu Bo.");
        } catch (EntityNotFoundException e) {
            // Ok, foi removido.
        }
    }
}