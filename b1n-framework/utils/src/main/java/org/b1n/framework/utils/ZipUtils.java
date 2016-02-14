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
package org.b1n.framework.utils;

import java.io.File;
import java.io.FileInputStream;
import java.io.FileOutputStream;
import java.io.IOException;
import java.util.zip.ZipEntry;
import java.util.zip.ZipOutputStream;

/**
 * Zip-Zap-Zum!
 * @author Marcio Ribeiro
 * @date Feb 4, 2008
 */
public final class ZipUtils {

    /**
     * This class should not be instatiated.
     */
    private ZipUtils() {
        // nothing
    }

    /**
     * Creates a zip file with the given resources.
     * @param zippedFileName name of the zip file to be created.
     * @param resourcesToZip name of the resources to be zipped (files and/or directories).
     * @throws IOException when something nasty occurs.
     */
    public static void zip(final String zippedFileName, final String... resourcesToZip) throws IOException {
        final ZipOutputStream zipOut = new ZipOutputStream(new FileOutputStream(zippedFileName));
        try {
            for (final String resourceToZip : resourcesToZip) {
                zipResource(new File(resourceToZip), zipOut);
            }
        } finally {
            zipOut.close();
        }
    }

    /**
     * Zip resource.
     * @param resourceToZip resource to be zipped.
     * @param zipOut out.
     * @throws IOException errors when zipping.
     */
    private static void zipResource(final File resourceToZip, final ZipOutputStream zipOut) throws IOException {
        if (resourceToZip.isFile()) {
            zipFile(resourceToZip, zipOut);
            return;
        }

        for (final String fileName : resourceToZip.list()) {
            final File fileToZip = new File(resourceToZip, fileName);
            if (fileToZip.isDirectory()) {
                zipResource(fileToZip, zipOut);
            } else if (fileToZip.isFile()) {
                zipFile(fileToZip, zipOut);
            }
        }
    }

    /**
     * Zip file.
     * @param fileToZip file to be zipped.
     * @param zipOut out.
     * @throws IOException errors when zipping.
     */
    private static void zipFile(final File fileToZip, final ZipOutputStream zipOut) throws IOException {
        final FileInputStream fileIn = new FileInputStream(fileToZip);
        try {
            final int bufferSize = 2156;
            final byte[] buffer = new byte[bufferSize];
            int bytesRead = 0;

            final ZipEntry zipEntry = new ZipEntry(fileToZip.getPath());
            zipOut.putNextEntry(zipEntry);
            while ((bytesRead = fileIn.read(buffer)) != -1) {
                zipOut.write(buffer, 0, bytesRead);
            }
        } finally {
            fileIn.close();
        }
    }
}