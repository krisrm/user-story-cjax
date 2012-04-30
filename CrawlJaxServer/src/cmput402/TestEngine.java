package cmput402;

import java.io.IOException;

import javax.servlet.ServletException;
import javax.servlet.annotation.WebServlet;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.xml.bind.JAXBContext;

import cmput402.data.Configuration;
import cmput402.data.TestCase;
import cmput402.data.TestSuite;

@WebServlet("/TestEngine")
public class TestEngine extends CrawlEngine {
	private static final long serialVersionUID = 1L;

	public TestEngine() {
		super();
	}

	protected void doPost(HttpServletRequest request,
			HttpServletResponse response) throws ServletException, IOException {
		try {
			//
			// TestSuite test = new TestSuite();
			// test.config = new Configuration();
			// test.config.app = "app addr";
			// test.config.server = "server addr";
			// test.config.script = "lolscript";
			// test.config.callback = "callback addr";
			// TestCase c1 = new TestCase();
			// c1.id=5;c1.script="c1script";
			// test.cases.add(c1);
			// TestCase c2 = new TestCase();
			// c2.id=5;c2.script="c2script";
			// test.cases.add(c2);

			final TestSuite test = (TestSuite) JAXBContext
					.newInstance(TestSuite.class).createUnmarshaller()
					.unmarshal(request.getReader());
			// debug
			// JAXBContext.newInstance(TestSuite.class).createMarshaller().marshal(test,System.out);

			new Thread() {
				public void run() {
					getCrawlManager().startTests(test);
				}
			}.start();
		} catch (Exception e) {
			e.printStackTrace();
		}

	}

}
