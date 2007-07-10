package b1n.sound;
import java.io.File;

import org.blinkenlights.jid3.MP3File;
import org.blinkenlights.jid3.MediaFile;
import org.blinkenlights.jid3.v1.ID3V1Tag;
import org.blinkenlights.jid3.v2.ID3V2Tag;

/**
 * @author Marcio Ribeiro (mmr)
 * @created May 20, 2006
 * @version $Id: SoundOrganizer.java,v 1.1 2006/07/10 16:34:34 mmr Exp $
 */
public class SoundOrganizer {
    public void organizeSound(String origDirName, String destDirName) throws Exception {
        organizeSound(new File(origDirName), new File(destDirName));
    }

    private void organizeSound(File origDir, File destDir) {
        if (!origDir.isDirectory()) {
            throw new IllegalStateException();
        }
        for (String curFileName : origDir.list()) {
            File curFile = new File(origDir, curFileName);
            if (curFile.isFile() && curFile.getName().matches(".*\\.[Mm][Pp]3")) {
                try {
                    organizeFile(curFile, destDir);
                } catch (Exception e) {
                    System.out.println("ERROR : " + curFile);
                }
            }
        }
    }

    private void organizeFile(File curFile, File destDir) throws Exception {
        MediaFile mediaFile = new MP3File(curFile);
        ID3V1Tag v1Tag = mediaFile.getID3V1Tag();
        ID3V2Tag v2Tag = mediaFile.getID3V2Tag();
        String artist = null;
        String title = null;
        if (v2Tag != null && v2Tag.getArtist() != null && v2Tag.getTitle() != null) {
            artist = v2Tag.getArtist();
            title = v2Tag.getTitle();
        } else if (v1Tag != null && v1Tag.getArtist() != null && v1Tag.getTitle() != null) {
            artist = v1Tag.getArtist();
            title = v1Tag.getTitle();
        }
        if (artist == null || title == null || "".equals(artist) || "".equals(title)) {
            System.out.println("******");
            System.out.println("NO F: " + curFile.getName());
            artist = "Misc";
            title = curFile.getName().split("\\.[Mm]")[0];
        }
        artist = artist.trim();
        title = title.trim();
        //System.out.println("OK A: " + artist);
        //System.out.println("OK T: " + title);
        //System.out.println("-----");

        File artistDir = new File(destDir, artist);
        artistDir.mkdirs();
        //copyFile(curFile, title + ".mp3", artistDir);
        moveFile(curFile, title + ".mp3", artistDir);
    }

    private void moveFile(File origFile, String destFileName, File destDir) throws Exception {
        origFile.renameTo(new File(destDir, destFileName));
    }

//    private void copyFile(File origFile, String destFileName, File destDir) throws Exception {
//        BufferedInputStream in = new BufferedInputStream(new FileInputStream(origFile));
//        BufferedOutputStream out = new BufferedOutputStream(new FileOutputStream(new File(destDir, destFileName)));
//        try {
//            final int bufSize = 1024;
//            int bytesRead = 0;
//            byte[] buf = new byte[bufSize];
//            while ((bytesRead = in.read(buf, 0, bufSize)) > 0) {
//                out.write(buf, 0, bytesRead);
//            }
//        } finally {
//            in.close();
//            out.close();
//        }
//
//    }

    public static void main(String[] args) throws Exception {
        SoundOrganizer o = new SoundOrganizer();
        o.organizeSound("/tmp/mp3", "/Sound");
    }
}