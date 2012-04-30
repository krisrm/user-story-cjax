package cmput402;

import cmput402.data.Assert;
import cmput402.data.CrawlDirector;
import cmput402.data.TestCase;
import cmput402.data.TestSuite;

import com.crawljax.browser.EmbeddedBrowser;
import com.crawljax.core.CrawlSession;
import com.crawljax.core.plugin.OnNewStatePlugin;
import com.crawljax.core.plugin.OnUrlLoadPlugin;
import com.crawljax.core.plugin.PostCrawlingPlugin;
import com.google.gson.Gson;

public class JavaScriptPlugin implements OnNewStatePlugin,OnUrlLoadPlugin {

	private CrawlerManager cm;
	private JavaScriptLibraryManager lib;
	private TestCase cur;
	
	public JavaScriptPlugin(CrawlerManager crawlerManager,
			JavaScriptLibraryManager lib, TestCase cur) {
		this.cm = crawlerManager;
		this.lib = lib;
		this.cur = cur;
	}

	@Override
	public void onNewState(CrawlSession s) {
		//onUrlLoad(s.getBrowser());
	}


	@Override
	public void onUrlLoad(EmbeddedBrowser b) {
		TestSuite tests = cm.getTestSuite();
		String script = lib.getFullLib()+"\n";
		script += cur.stateRestore+"\n";
		script += tests.config.script +"\n";
		script += tests.config.getConstants()+"\n";
		script += cur.script;
		script += "\nreturn Crawler.getReturn();";
		System.out.println("EXECUTING\t\t" + cur.stateRestore);
		try {
			//EmbeddedBrowser b = s.getBrowser();
			//System.out.println(b.executeJavaScript("return document.getElementById('branding')==null;"));
			String result = b.executeJavaScript(script).toString();
			System.out.println(result);
			if (result.startsWith("D")){
				result = result.substring(2);
				CrawlDirector cd = new Gson().fromJson(result, CrawlDirector.class);
				System.out.println("DIRECTING ");
				
				cm.direct(cur,cd,b);
				if (cd.crawlspec.url != null){
					b.goToUrl(cd.crawlspec.url);
					this.onUrlLoad(b);
				}
				
			} else if (result.startsWith("A")){
				System.out.println("FINISHING ");
				result = result.substring(2);
				Assert a = new Gson().fromJson(result, Assert.class);
				cm.finishTest(cur,a.asserts, a.jsonErrors());
	
			}
			return;
//			Assert a = new Assert();
//			for (Object o : result.keySet()){
//				if (o.toString().equals("asserts")){
//					a.asserts = (Long) result.get(o);
//				} else if (o.toString().equals("assertStack")) {
//					a.parseErrors(result.get(o));
//				}
//			}
		} catch (Exception e) {
			e.printStackTrace();
			cm.finishTest(cur,0L, "{}");
		}
	}

}
