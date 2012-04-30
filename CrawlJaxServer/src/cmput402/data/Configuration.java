package cmput402.data;

import javax.xml.bind.annotation.XmlElement;

public class Configuration {
	@XmlElement
	public String server;
	@XmlElement
	public String app;
	@XmlElement
	public String script;
	@XmlElement
	public String callback;
	@XmlElement
	public String session;
	
	public String getConstants() {
		if (!server.endsWith("/"))
			server +="/";
		if (!app.endsWith("/"))
			app +="/";
		String r = "\nCrawler.SERVER = '" + server + "';";
		r+= "\nCrawler.APP = '" + app+"';\n";
		return r;
	}
}
