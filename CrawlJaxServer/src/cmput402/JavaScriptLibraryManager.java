package cmput402;

import java.io.BufferedReader;
import java.io.File;
import java.io.FileReader;
import java.io.IOException;

public class JavaScriptLibraryManager {

	private String fullLib ="";
	
	public JavaScriptLibraryManager(String... libraries) throws IOException{
		for (String file : libraries){
			fullLib += readFile(file);
		}
	}
	
	public String getFullLib() {
		return fullLib;
	}

	public static String readFile(String path) throws IOException {
		String r = "";
		BufferedReader br = new BufferedReader(new FileReader(new File(path)));
		String line = br.readLine();
		while (line != null) {
			r += line.trim() +"\n";
			line = br.readLine();
		}
		br.close();
		return r;
	}

}
