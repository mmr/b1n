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
import org.b1n.framework.utils.ZipUtils;

/**
 * @author Marcio Ribeiro (mmr)
 * @created May 7, 2006
 * @version $Id: ZipUtilsTest.java,v 1.1 2006/08/08 03:28:56 mmr Exp $
 */
public class ZipUtilsTest extends TestCase {
    /**
     * Construtor.
     * @param testName nome do teste.
     */
    public ZipUtilsTest(final String testName) {
        super(testName);
    }

    /**
     * Devolve a suite de testes.
     * @return a suite de testes.
     */
    public static Test suite() {
        return new TestSuite(ZipUtilsTest.class);
    }

    /**
     * Cria estrutura de diret—rios a ser compactada.
     * @throws IOException caso n‹o consiga ler ou gravar dados.
     */
    private void createDirStructure() throws IOException {
        // Cria estrutura de diret—rios a ser zipada no teste
        new File("a" + File.separator + "aa").mkdirs();
        new File("a" + File.separator + "ab").mkdirs();
        new File("b" + File.separator + "ba" + File.separator + "baa").mkdirs();

        // Cria arquivos para popularem estrutura
        final String[] files = new String[] { "a" + File.separator + "a1", "a" + File.separator + "a2", "a" + File.separator + "ab" + File.separator + "ab1", "b" + File.separator + "ba" + File.separator + "ba1" };

        for (final String file : files) {
            final PrintWriter writer = new PrintWriter(new FileOutputStream(file));
            writer.write("ZipUtilsTest : " + file + "\n");
            writer.close();
        }
    }

    /**
     * Verifica se consegue compactar arquivos.
     */
    public void testZip() {
        try {
            final String zipFileName = "test.zip";

            // Cria estrutura a ser compactada
            createDirStructure();

            // Zipa estrutura
            ZipUtils.zip(zipFileName, "a", "b");

            // Verifica se arquivo zip foi criado
            assertTrue(new File(zipFileName).exists());

            // Remove estrutura criada para teste
            FileUtils.deltree("a");
            FileUtils.deltree("b");

            // Apaga arquivo zip
            new File(zipFileName).delete();
        } catch (final Throwable t) {
            t.printStackTrace();
            fail("Nao conseguiu criar zip");
        }
    }

    /**
     * Verifica se consegue descompactar arquivos.
     */
    public void testUnzip() {
        // TODO (mmr) implementar unzip
        // ZipUtils.unzip(destDir, resourcesToUnzip)
    }
}