package cmput402;

import java.io.IOException;

import org.apache.commons.configuration.ConfigurationException;
import org.apache.http.HttpResponse;

import cmput402.data.ClickSpec;
import cmput402.data.CrawlDirector;
import cmput402.data.TestCase;
import cmput402.data.TestSuite;

import com.crawljax.browser.EmbeddedBrowser;
import com.crawljax.browser.EmbeddedBrowser.BrowserType;
import com.crawljax.core.CrawljaxController;
import com.crawljax.core.CrawljaxException;
import com.crawljax.core.configuration.CrawlSpecification;
import com.crawljax.core.configuration.CrawljaxConfiguration;
import com.crawljax.core.configuration.InputSpecification;

public class CrawlerManager {

	private JavaScriptLibraryManager lib;
	private TestSuite tests;
	private int currentTest;
	private CrawljaxController crawljax;
	private CrawljaxConfiguration config;
	private CrawlSpecification crawlspec;

	public CrawlerManager(String... libraries) {
		try {
			lib = new JavaScriptLibraryManager(libraries);
		} catch (IOException e) {
			e.printStackTrace();
		}
	}

	public void startTests(TestSuite tests) {
		this.tests = tests;
		currentTest = 0;
		startTest();
	}

	public void startTest() {
		TestCase cur = getCurrent();
		if (cur.script == null || cur.script.trim().isEmpty()) {
			finishTest(cur, 0L, "{}");
			return;
		}
		crawl(tests.config.app);
	}

	public void finishTest(TestCase cur, Long asserts, String jsonError) {
		// call the callback with the id and any errors
		System.out.println("FINISHED " + cur.id);
		System.out.println("ERRORS:" + jsonError);
		HttpResponse r = HTTPUtil.postCallback(tests.config.callback, cur.id,
				tests.config.session, asserts, jsonError);
		System.out.println(HTTPUtil.readResponse(r));
		currentTest++;
		if (currentTest >= tests.cases.size()) {
			finishedAllTests();
		} else {
			startTest();
		}
	}

	private void finishedAllTests() {
		// call the callback with finished = true;
		HTTPUtil.postFinished(tests.config.callback, tests.config.session);
	}

	public void crawl(final String url) {
		config = new CrawljaxConfiguration();
		config.setBrowser(BrowserType.firefox);
		JavaScriptPlugin plugin = new JavaScriptPlugin(CrawlerManager.this,
				lib, getCurrent());
		config.addPlugin(plugin);
		crawlspec = new CrawlSpecification(url);
		crawlspec.setRandomInputInForms(false);
		config.setCrawlSpecification(crawlspec);
		
		try {
			crawljax = new CrawljaxController(config);
			crawljax.run();

		} catch (ConfigurationException e) {
			e.printStackTrace();
		} catch (CrawljaxException e) {
			e.printStackTrace();
		}

	}

	public TestSuite getTestSuite() {
		return tests;
	}

	public TestCase getCurrent() {
		return tests.cases.get(currentTest);
	}

	public void direct(TestCase cur, CrawlDirector cd, EmbeddedBrowser b) {
		cur.stateRestore = cd.restore;
		for (ClickSpec cs : cd.crawlspec.clickable){
			crawlspec.click(cs.tagName).withAttribute("id", cs.id);
		}
	}

}
