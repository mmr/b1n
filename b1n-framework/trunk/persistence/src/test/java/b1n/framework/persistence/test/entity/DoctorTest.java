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
import b1n.framework.persistence.bo.HealthInsuranceBo;
import b1n.framework.persistence.bo.HospitalBo;
import b1n.framework.persistence.bo.factory.FactoryLocator;
import b1n.framework.persistence.bo.factory.DoctorBoFactory;
import b1n.framework.persistence.bo.factory.HealthInsuranceBoFactory;
import b1n.framework.persistence.bo.factory.HospitalBoFactory;
import b1n.framework.persistence.test.PersistenceTestCase;

/**
 * @author Marcio Ribeiro (mmr)
 * @created Mar 28, 2007
 */
public class DoctorBoTest extends PersistenceTestCase {
    private static Long id;

    private static final DoctorBoFactory docFac = FactoryLocator.findFactory(DoctorBo.class);

    private static final HospitalBoFactory hospitalFac = FactoryLocator.findFactory(HospitalBo.class);

    private static final String HOSPITAL_NAME = "Sirio Libanes";
    
    public DoctorBoTest(String arg) {
        super(arg);
    }

    public void testSaveAndLoad() throws Exception {
        // Criando Medico
        DoctorBo doc = docFac.getBo();
        doc.setName("Omara Portuondo");
        doc.getContactInfo().setEmail("omara@portuondo.com");

        // Criando hospital
        HospitalBo hospital = hospitalFac.getBo();
        hospital.setName(HOSPITAL_NAME);
        hospital.addDoctor(doc);
        doc.setHospital(hospital);
        hospital.save();

        // Carregando Medico
        id = doc.getId();
        DoctorBo loadedBo = docFac.getBo(id);
        assertEquals(doc.getName(), loadedBo.getName());
    }

    public void testRemoveDoctor() throws EntityNotFoundException {
        DoctorBo doctor = docFac.getBo(id);
        doctor.getHospital().remove();

        try {
            docFac.getBo(id);
            fail("Could not remove Bo.");
        } catch (EntityNotFoundException e) {
            // Ok, foi removido.
        }
    }

    public void testHealthInsurance() throws Exception {
        // Criando Convenios
        HealthInsuranceBoFactory hiFac = FactoryLocator.findFactory(HealthInsuranceBo.class);
        HealthInsuranceBo hi1 = hiFac.getBo();
        hi1.setName("AMIL");
        hi1.save();

        HealthInsuranceBo hi2 = hiFac.getBo();
        hi2.setName("Bradesco");
        hi2.save();

        HealthInsuranceBo hi3 = hiFac.getBo();
        hi3.setName("Correios");
        hi3.save();

        HealthInsuranceBo hi4 = hiFac.getBo();
        hi4.setName("Samcil");
        hi4.save();

        HealthInsuranceBo hi5 = hiFac.getBo();
        hi5.setName("Dix Amico");
        hi5.save();

        // Criando Medicos
        DoctorBo doc1 = docFac.getBo();
        doc1.setName("Joaquim do Bandolim");
        doc1.addHealthInsurance(hi1);
        doc1.addHealthInsurance(hi3);
        doc1.addHealthInsurance(hi5);
        doc1.save();

        DoctorBo doc2 = docFac.getBo();
        doc2.setName("Adriana Calcanhoto");
        doc2.addHealthInsurance(hi2);
        doc2.addHealthInsurance(hi4);
        doc2.addHealthInsurance(hi5);
        doc2.save();

        DoctorBo doc3 = docFac.getBo();
        doc3.setName("Pixinguinha");
        doc3.addHealthInsurance(hi1);
        doc3.addHealthInsurance(hi2);
        doc3.addHealthInsurance(hi3);
        doc3.addHealthInsurance(hi4);
        doc3.addHealthInsurance(hi5);
        doc3.save();
    }
}