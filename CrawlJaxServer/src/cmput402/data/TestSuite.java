package cmput402.data;

import java.util.ArrayList;
import java.util.List;

import javax.xml.bind.annotation.XmlElement;
import javax.xml.bind.annotation.XmlRootElement;


@XmlRootElement(name="test-suite")
public class TestSuite {

	@XmlElement(name="config")
	public Configuration config;
	@XmlElement(name="case")
	public List<TestCase> cases = new ArrayList<TestCase>();
}
