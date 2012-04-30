package cmput402.data;

import javax.xml.bind.annotation.XmlAttribute;
import javax.xml.bind.annotation.XmlElement;

public class TestCase {
	
	@XmlAttribute
	public int id;
	@XmlElement
	public String script;
	
	public String stateRestore ="";
	
}
