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
package org.b1n.framework.base.util;

import java.io.File;
import java.io.FileOutputStream;
import java.io.IOException;
import java.io.PrintWriter;

import junit.framework.Test;
import junit.framework.TestCase;
import junit.framework.TestSuite;

import org.b1n.framework.utils.FileUtils;

/**
 * @author Marcio Ribeiro (mmr)
 * @created March 27, 2007
 */
public class FileUtilsTest extends TestCase {
    private static final String BASE_DIR_NAME = "a";

    /**
     * Construtor.
     * @param testName nome do teste.
     */
    public FileUtilsTest(final String testName) {
        super(testName);
    }

    /**
     * Suite.
     * @return test suite.
     */
    public static Test suite() {
        return new TestSuite(FileUtilsTest.class);
    }

    /**
     * Cria estrutura de diretorios.
     * @throws IOException caso nao consiga criar estrutura de diretorios.
     */
    private void createDirStructure() throws IOException {
        // Cria estrutura de diretorios a ser removida no teste
        new File(BASE_DIR_NAME + File.separator + "aa").mkdirs();
        new File(BASE_DIR_NAME + File.separator + "ab" + File.separator + "aba").mkdirs();

        // Cria arquivos para popularem estrutura
        final String[] files = new String[] { BASE_DIR_NAME + File.separator + "a1", BASE_DIR_NAME + File.separator + "a2", BASE_DIR_NAME + File.separator + "ab" + File.separator + "ab1", BASE_DIR_NAME + File.separator + "ab" + File.separator + "aba" + File.separator + "aba1" };

        for (final String file : files) {
            final PrintWriter writer = new PrintWriter(new FileOutputStream(file));
            writer.write("FileUtilsTest : " + file + "\n");
            writer.close();
        }
    }

    /**
     * Testa exclusao.
     */
    public void testDeltree() {
        try {
            // Cria estrutura a ser compactada
            createDirStructure();

            // Verifica se estrutura de diret—rios a ser removida foi criada
            assertTrue(new File(BASE_DIR_NAME).exists());

            // Remove estrutura criada para teste
            FileUtils.deltree(BASE_DIR_NAME);

            // Verifica que estrutura foi removida
            assertFalse(new File(BASE_DIR_NAME).exists());
        } catch (final Throwable t) {
            t.printStackTrace();
            fail("Algo de errado ocorreu.");
        }
    }
}