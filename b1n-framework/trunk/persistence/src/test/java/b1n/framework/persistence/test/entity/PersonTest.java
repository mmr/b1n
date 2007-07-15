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

import b1n.framework.persistence.entity.EntityNotFoundException;
import b1n.framework.persistence.entity.Person;
import b1n.framework.persistence.entity.PersonFactory;
import b1n.framework.persistence.entity.factory.FactoryLocator;
import b1n.framework.persistence.test.PersistenceTestCase;

/**
 * @author Marcio Ribeiro (mmr)
 * @created Mar 28, 2007
 */
public class PersonTest extends PersistenceTestCase {
    private static Long id;

    public void testSaveAndLoad() throws Exception {
        PersonFactory fac = FactoryLocator.findFactory(Person.class);
        Person person = fac.createEntity();

        // Salvando
        person.setName("Chico Buarque");
        person.getContactInfo().setEmail("chico@buarque.com");
        person.getContactInfo().setPhone("(+55-11) 1234-5679");
        person.save();

        // Carregando salvo
        Person loaded = fac.findById(person.getId());

        // Comparando dados entre criado e carregado
        assertEquals(person.getName(), loaded.getName());
        assertEquals(person.getContactInfo().getEmail(), loaded.getContactInfo().getEmail());

        // Removendo
        id = person.getId();
        person.remove();
    }

    public void testWasRemoved() {
        PersonFactory fac = FactoryLocator.findFactory(Person.class);
        try {
            fac.findById(id);
            fail("Could not remove .");
        } catch (EntityNotFoundException e) {
            // Ok, foi removido.
        }
    }
}