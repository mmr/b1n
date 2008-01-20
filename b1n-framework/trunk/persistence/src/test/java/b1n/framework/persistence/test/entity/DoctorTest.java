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

import b1n.framework.persistence.DaoLocator;
import b1n.framework.persistence.EntityNotFoundException;
import b1n.framework.persistence.entity.Doctor;
import b1n.framework.persistence.entity.DoctorDao;
import b1n.framework.persistence.entity.HealthInsurance;
import b1n.framework.persistence.entity.Hospital;
import b1n.framework.persistence.test.PersistenceTestCase;

/**
 * @author Marcio Ribeiro (mmr)
 * @created Mar 28, 2007
 */
public class DoctorTest extends PersistenceTestCase {
    private static Long id;

    private static final DoctorDao docDao = DaoLocator.getDao(Doctor.class);

    private static final String HOSPITAL_NAME = "Sirio Libanes";

    public DoctorTest(String arg) {
        super(arg);
    }

    public void testSaveAndLoad() throws Exception {
        // Criando Medico
        Doctor doc = new Doctor();
        doc.setName("Omara Portuondo");
        doc.getContactInfo().setEmail("omara@portuondo.com");

        // Criando hospital
        Hospital hospital = new Hospital();
        hospital.setName(HOSPITAL_NAME);
        hospital.addDoctor(doc);
        doc.setHospital(hospital);
        hospital.save();

        // Carregando Medico
        id = doc.getId();
        Doctor loaded = docDao.findById(id);
        assertEquals(doc.getName(), loaded.getName());
    }

    public void testRemoveDoctor() throws EntityNotFoundException {
        Doctor doctor = docDao.findById(id);
        doctor.getHospital().remove();

        try {
            docDao.findById(id);
            fail("Could not remove .");
        } catch (EntityNotFoundException e) {
            // Ok, foi removido.
        }
    }

    public void testHealthInsurance() throws Exception {
        // Criando Convenios
        HealthInsurance hi1 = new HealthInsurance();
        hi1.setName("AMIL");
        hi1.save();

        HealthInsurance hi2 = new HealthInsurance();
        hi2.setName("Bradesco");
        hi2.save();

        HealthInsurance hi3 = new HealthInsurance();
        hi3.setName("Correios");
        hi3.save();

        HealthInsurance hi4 = new HealthInsurance();
        hi4.setName("Samcil");
        hi4.save();

        HealthInsurance hi5 = new HealthInsurance();
        hi5.setName("Dix Amico");
        hi5.save();

        // Criando Medicos
        Doctor doc1 = new Doctor();
        doc1.setName("Joaquim do Bandolim");
        doc1.addHealthInsurance(hi1);
        doc1.addHealthInsurance(hi3);
        doc1.addHealthInsurance(hi5);
        doc1.save();

        Doctor doc2 = new Doctor();
        doc2.setName("Adriana Calcanhoto");
        doc2.addHealthInsurance(hi2);
        doc2.addHealthInsurance(hi4);
        doc2.addHealthInsurance(hi5);
        doc2.save();

        Doctor doc3 = new Doctor();
        doc3.setName("Pixinguinha");
        doc3.addHealthInsurance(hi1);
        doc3.addHealthInsurance(hi2);
        doc3.addHealthInsurance(hi3);
        doc3.addHealthInsurance(hi4);
        doc3.addHealthInsurance(hi5);
        doc3.save();
    }
}