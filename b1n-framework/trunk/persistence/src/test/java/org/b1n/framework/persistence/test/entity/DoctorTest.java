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
import org.b1n.framework.persistence.entity.DoctorDao;
import org.b1n.framework.persistence.entity.HealthInsurance;
import org.b1n.framework.persistence.entity.Hospital;
import org.b1n.framework.persistence.test.PersistenceTestCase;

/**
 * @author Marcio Ribeiro (mmr)
 * @created Mar 28, 2007
 */
public class DoctorTest extends PersistenceTestCase {
    private static Long id;

    private static final DoctorDao DOC_DAO = DaoLocator.getDao(Doctor.class);

    private static final String HOSPITAL_NAME = "Sirio Libanes";

    /**
     * Construtor.
     * @param name name.
     */
    public DoctorTest(final String name) {
        super(name);
    }

    /**
     * Test save and load.
     * @throws Exception exception.
     */
    public void testSaveAndLoad() throws Exception {
        // Criando Medico
        final Doctor doc = new Doctor();
        doc.setName("Omara Portuondo");
        doc.getContactInfo().setEmail("omara@portuondo.com");

        // Criando hospital
        final Hospital hospital = new Hospital();
        hospital.setName(HOSPITAL_NAME);
        hospital.addDoctor(doc);
        doc.setHospital(hospital);
        hospital.save();

        // Carregando Medico
        id = doc.getId();
        final Doctor loaded = DOC_DAO.findById(id);
        assertEquals(doc.getName(), loaded.getName());
    }

    /**
     * Test remove doctor.
     * @throws EntityNotFoundException entity not found.
     */
    public void testRemoveDoctor() throws EntityNotFoundException {
        final Doctor doctor = DOC_DAO.findById(id);
        doctor.getHospital().remove();

        try {
            DOC_DAO.findById(id);
            fail("Could not remove .");
        } catch (final EntityNotFoundException e) {
            // Ok, foi removido.
        }
    }

    /**
     * Test health insurance.
     * @throws Exception exception.
     */
    public void testHealthInsurance() throws Exception {
        // Criando Convenios
        final HealthInsurance hi1 = new HealthInsurance();
        hi1.setName("AMIL");
        hi1.save();

        final HealthInsurance hi2 = new HealthInsurance();
        hi2.setName("Bradesco");
        hi2.save();

        final HealthInsurance hi3 = new HealthInsurance();
        hi3.setName("Correios");
        hi3.save();

        final HealthInsurance hi4 = new HealthInsurance();
        hi4.setName("Samcil");
        hi4.save();

        final HealthInsurance hi5 = new HealthInsurance();
        hi5.setName("Dix Amico");
        hi5.save();

        // Criando Medicos
        final Doctor doc1 = new Doctor();
        doc1.setName("Joaquim do Bandolim");
        doc1.addHealthInsurance(hi1);
        doc1.addHealthInsurance(hi3);
        doc1.addHealthInsurance(hi5);
        doc1.save();

        final Doctor doc2 = new Doctor();
        doc2.setName("Adriana Calcanhoto");
        doc2.addHealthInsurance(hi2);
        doc2.addHealthInsurance(hi4);
        doc2.addHealthInsurance(hi5);
        doc2.save();

        final Doctor doc3 = new Doctor();
        doc3.setName("Pixinguinha");
        doc3.addHealthInsurance(hi1);
        doc3.addHealthInsurance(hi2);
        doc3.addHealthInsurance(hi3);
        doc3.addHealthInsurance(hi4);
        doc3.addHealthInsurance(hi5);
        doc3.save();
    }
}