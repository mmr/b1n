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
import b1n.framework.persistence.bo.PersonBo;
import b1n.framework.persistence.bo.factory.FactoryLocator;
import b1n.framework.persistence.bo.factory.PersonBoFactory;
import b1n.framework.persistence.test.PersistenceTestCase;

/**
 * @author Marcio Ribeiro (mmr)
 * @created Mar 28, 2007
 */
public class PersonBoTest extends PersistenceTestCase {
    private static Long id;

    public void testSaveAndLoad() throws Exception {
        PersonBoFactory fac = FactoryLocator.findFactory(PersonBo.class);
        PersonBo person = fac.getBo();

        // Salvando Bo
        person.setName("Chico Buarque");
        person.getContactInfo().setEmail("chico@buarque.com");
        person.getContactInfo().setPhone("(+55-11) 1234-5679");
        person.save();

        // Carregando Bo salvo
        PersonBo loadedBo = fac.getBo(person.getId());

        // Comparando dados entre Bo criado e Bo carregado
        assertEquals(person.getName(), loadedBo.getName());
        assertEquals(person.getContactInfo().getEmail(), loadedBo.getContactInfo().getEmail());

        // Removendo Bo
        id = person.getId();
        person.remove();
    }

    public void testWasRemoved() {
        PersonBoFactory fac = FactoryLocator.findFactory(PersonBo.class);
        try {
            fac.getBo(id);
            fail("Could not remove Bo.");
        } catch (EntityNotFoundException e) {
            // Ok, foi removido.
        }
    }
}