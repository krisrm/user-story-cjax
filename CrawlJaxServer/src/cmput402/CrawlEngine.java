package cmput402;

import javax.servlet.http.HttpServlet;

public class CrawlEngine extends HttpServlet {
	private static final long serialVersionUID = 2497755311696198149L;
	public static final String LIBRARY = "/WEB-INF/crawljax.js";

	public CrawlerManager getCrawlManager() {

		CrawlerManager cm = (CrawlerManager) getServletContext().getAttribute(
				"cm");
		if (cm == null) {
			cm = new CrawlerManager(getServletContext().getRealPath(LIBRARY));
			this.getServletContext().setAttribute("cm", cm);
		}
		return cm;
	}

}
